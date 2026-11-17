<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\LabItem;
use App\Models\Laboratory;
use App\Models\User;
use App\Notifications\BookingStatusUpdatedNotification;
use App\Notifications\NewBookingRequestNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Booking::with(['laboratory', 'user']);

        if ($user->role === 'mahasiswa') {
            $query->where('user_id', $user->id);
        } else {
            // Admin: apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('nim_nip', 'like', "%{$search}%");
                    })->orWhere('purpose', 'like', "%{$search}%");
                });
            }

            if ($request->filled('date_from')) {
                $query->whereDate('start_time', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('start_time', '<=', $request->date_to);
            }
        }

        $bookings = $query->latest()->get();

        return view('dashboard', compact('bookings'));
    }

    public function create()
    {
        $lab = Laboratory::where('is_active', true)->first();
        $items = LabItem::where('is_active', true)->get();

        return view('bookings.create', compact('lab', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date|after_or_equal:today',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,jpeg,png|max:10240',
            'items' => 'nullable|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        $lab = Laboratory::where('is_active', true)->firstOrFail();

        try {
            DB::beginTransaction();

            $labLocked = Laboratory::where('id', $lab->id)->lockForUpdate()->firstOrFail();

            // 1. Validasi jadwal lab yang sedang berlangsung atau bentrok
            $overlap = Booking::where('laboratory_id', $labLocked->id)
                ->whereIn('status', ['pending', 'approved_admin', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->first();

            if ($overlap) {
                DB::rollBack();

                $existingStart = Carbon::parse($overlap->start_time)->format('d M Y, H:i');
                $existingEnd = Carbon::parse($overlap->end_time)->format('H:i');

                return back()->withInput()->withErrors([
                    'error' => "Input Tidak Valid: Jadwal peminjaman bentrok! Laboratorium sedang digunakan atau sudah dipesan pada {$existingStart} - {$existingEnd} oleh {$overlap->user->name}. Silakan pilih waktu lain.",
                ]);
            }

            // 2. Validasi ketersediaan dan bentrok peminjaman perangkat laboratorium
            $selectedItems = array_filter($request->input('items', []), fn ($qty) => (int) $qty > 0);

            foreach ($selectedItems as $itemId => $requestedQty) {
                $labItem = LabItem::where('id', $itemId)->where('is_active', true)->lockForUpdate()->first();

                if (! $labItem) {
                    DB::rollBack();

                    return back()->withInput()->withErrors([
                        'error' => "Input Tidak Valid: Perangkat dengan ID #{$itemId} tidak ditemukan atau sedang tidak aktif.",
                    ]);
                }

                if ($requestedQty > $labItem->total_quantity) {
                    DB::rollBack();

                    return back()->withInput()->withErrors([
                        'error' => "Input Tidak Valid: Jumlah pinjam untuk perangkat '{$labItem->name}' melebihi kapasitas total ({$labItem->total_quantity} unit).",
                    ]);
                }

                // Cek kuota perangkat yang sedang dipinjam pada rentang waktu yang sama
                $usedUnits = BookingItem::where('lab_item_id', $itemId)
                    ->whereHas('booking', function ($q) use ($request) {
                        $q->whereIn('status', ['pending', 'approved_admin', 'approved'])
                            ->where(function ($query) use ($request) {
                                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                    ->orWhere(function ($sub) use ($request) {
                                        $sub->where('start_time', '<=', $request->start_time)
                                            ->where('end_time', '>=', $request->end_time);
                                    });
                            });
                    })
                    ->sum('quantity');

                $remainingAvailable = $labItem->total_quantity - $usedUnits;

                if ($requestedQty > $remainingAvailable) {
                    DB::rollBack();

                    $info = $remainingAvailable <= 0
                        ? 'sedang digunakan atau kuota penuh'
                        : "hanya tersisa {$remainingAvailable} unit";

                    return back()->withInput()->withErrors([
                        'error' => "Input Tidak Valid: Perangkat '{$labItem->name}' {$info} untuk jadwal yang Anda pilih (diminta {$requestedQty} unit).",
                    ]);
                }
            }

            $attachmentPath = null;
            $attachmentName = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentName = $file->getClientOriginalName();
                $attachmentPath = $file->store('booking-attachments', 'public');
            }

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'laboratory_id' => $labLocked->id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'purpose' => $request->purpose,
                'status' => 'pending',
                'equipment_checklist' => ['komputer' => 'baik', 'ac' => 'baik', 'kebersihan' => 'baik'],
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
            ]);

            // Simpan detail item perangkat yang dipinjam
            foreach ($selectedItems as $itemId => $requestedQty) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'lab_item_id' => $itemId,
                    'quantity' => (int) $requestedQty,
                ]);
            }

            AuditLog::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'action' => 'created',
                'status_from' => null,
                'status_to' => 'pending',
                'notes' => 'Pengajuan peminjaman baru oleh '.Auth::user()->name,
            ]);

            DB::commit();

            // Notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewBookingRequestNotification($booking));
            }

            return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan dan menunggu validasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan sistem: '.$e->getMessage()]);
        }
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();

        // Mahasiswa hanya bisa lihat miliknya sendiri
        if ($user->role === 'mahasiswa' && $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->load(['laboratory', 'user', 'items.labItem', 'auditLogs.user']);

        return view('bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        if ($user->role === 'mahasiswa') {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $booking = Booking::where('id', $booking->id)->lockForUpdate()->firstOrFail();
            $oldStatus = $booking->status;
            $booking->status = $request->status;
            $booking->save();

            AuditLog::create([
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'action' => 'status_updated',
                'status_from' => $oldStatus,
                'status_to' => $request->status,
                'notes' => $request->notes ?? 'Status diupdate oleh '.$user->name,
            ]);

            DB::commit();

            if ($booking->user) {
                $booking->user->notify(new BookingStatusUpdatedNotification($booking, $user, $request->notes));
            }

            return back()->with('success', 'Status peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal memperbarui status.']);
        }
    }

    public function downloadAttachment(Booking $booking)
    {
        $user = Auth::user();

        if ($user->role === 'mahasiswa' && $booking->user_id !== $user->id) {
            abort(403);
        }

        if (! $booking->attachment_path || ! Storage::disk('public')->exists($booking->attachment_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($booking->attachment_path, $booking->attachment_name);
    }
}
