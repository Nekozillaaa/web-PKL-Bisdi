<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $bookingId = $notification->data['booking_id'] ?? null;
        $url = $notification->data['url'] ?? null;

        if (! $url && $bookingId) {
            $url = route('bookings.show', $bookingId);
        }

        return redirect($url ?: route('dashboard'));
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
