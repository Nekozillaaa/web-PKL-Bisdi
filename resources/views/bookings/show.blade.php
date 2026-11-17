<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="section-heading">Detail Peminjaman</p>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Rincian <span class="text-gradient">Booking</span> #{{ $booking->id }}
                </h2>
            </div>
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-white/5 border border-white/10 text-gray-300 font-bold text-xs rounded-xl hover:bg-white/10 transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @php
            $statusConfig = [
                'pending'          => ['bg' => 'bg-yellow-400/10 border-yellow-400/20 text-yellow-300',   'label' => 'Menunggu Persetujuan', 'icon' => 'fa-clock'],
                'approved'         => ['bg' => 'bg-green-400/10 border-green-400/20 text-green-300',      'label' => 'Disetujui',            'icon' => 'fa-circle-check'],
                'approved_dosen'   => ['bg' => 'bg-blue-400/10 border-blue-400/20 text-blue-300',         'label' => 'Disetujui',            'icon' => 'fa-circle-check'],
                'approved_laboran' => ['bg' => 'bg-green-400/10 border-green-400/20 text-green-300',      'label' => 'Disetujui',            'icon' => 'fa-circle-check'],
                'completed'        => ['bg' => 'bg-green-400/10 border-green-400/20 text-green-300',      'label' => 'Selesai',              'icon' => 'fa-flag-checkered'],
                'rejected'         => ['bg' => 'bg-red-400/10 border-red-400/20 text-red-300',            'label' => 'Ditolak',              'icon' => 'fa-circle-xmark'],
            ];
            $sc = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-500/10 border-gray-500/20 text-gray-400', 'label' => $booking->status, 'icon' => 'fa-circle'];
        @endphp

        {{-- Status Banner --}}
        <div class="card-dark rounded-2xl p-6 border {{ $sc['bg'] }} flex items-center gap-4">
            <div class="w-12 h-12 {{ $sc['bg'] }} border rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $sc['icon'] }} text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-widest opacity-70">Status Peminjaman</p>
                <p class="text-xl font-black">{{ $sc['label'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Info Booking --}}
            <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 bg-premium-gray/30">
                    <h3 class="font-bold text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-flask text-brand-orange"></i>
                        Informasi Peminjaman
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Laboratorium</p>
                        <p class="text-white font-semibold">{{ $booking->laboratory->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Pemohon</p>
                        <p class="text-white font-semibold">{{ $booking->user->name }}</p>
                        <p class="text-gray-500 text-xs">NIM/NIP: {{ $booking->user->nim_nip ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Waktu Mulai</p>
                        <p class="text-white font-semibold">{{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Waktu Selesai</p>
                        <p class="text-white font-semibold">{{ \Carbon\Carbon::parse($booking->end_time)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Diajukan Pada</p>
                        <p class="text-white font-semibold">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Tujuan & Lampiran --}}
            <div class="space-y-5">
                <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 bg-premium-gray/30">
                        <h3 class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-brand-orange"></i>
                            Tujuan Peminjaman
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $booking->purpose }}</p>
                    </div>
                </div>

                @if($booking->attachment_path)
                <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 bg-premium-gray/30">
                        <h3 class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-paperclip text-brand-orange"></i>
                            Dokumen Lampiran
                        </h3>
                    </div>
                    <div class="p-6">
                        <a href="{{ route('bookings.attachment', $booking->id) }}"
                           class="flex items-center gap-3 p-3 bg-brand-orange/5 border border-brand-orange/20 rounded-xl hover:bg-brand-orange/10 transition-colors group">
                            <div class="w-10 h-10 bg-brand-orange/10 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-arrow-down text-brand-orange"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm font-semibold truncate">{{ $booking->attachment_name }}</p>
                                <p class="text-gray-500 text-xs">Klik untuk unduh lampiran</p>
                            </div>
                            <i class="fa-solid fa-download text-brand-orange text-xs group-hover:translate-y-0.5 transition-transform"></i>
                        </a>
                    </div>
                </div>
                @endif

                @if($booking->items->isNotEmpty())
                <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 bg-premium-gray/30">
                        <h3 class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-brand-orange"></i>
                            Perangkat yang Dipinjam
                        </h3>
                    </div>
                    <div class="p-6 divide-y divide-white/5">
                        @foreach($booking->items as $bItem)
                        <div class="flex items-center justify-between py-2.5">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $bItem->labItem->name ?? 'Perangkat' }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $bItem->labItem->code ?? '-' }}</p>
                            </div>
                            <span class="px-3 py-1 bg-brand-orange/10 border border-brand-orange/20 text-brand-orange text-xs font-bold rounded-full">
                                {{ $bItem->quantity }} unit
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Admin Action Panel --}}
        @if(auth()->user()->role === 'admin' && in_array($booking->status, ['pending', 'approved', 'approved_dosen', 'approved_laboran']))
        <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 bg-premium-gray/30">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-brand-orange"></i>
                    Aksi Admin
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="space-y-4">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label-dark text-xs">Ubah Status</label>
                            <select name="status" class="input-dark text-sm">
                                <option value="pending"   {{ $booking->status === 'pending'   ? 'selected' : '' }}>Menunggu (Pending)</option>
                                <option value="approved"  {{ in_array($booking->status, ['approved','approved_dosen','approved_laboran']) ? 'selected' : '' }}>Setujui</option>
                                <option value="rejected"  {{ $booking->status === 'rejected'  ? 'selected' : '' }}>Tolak</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-dark text-xs">Catatan (Opsional)</label>
                            <input type="text" name="notes" placeholder="Alasan atau catatan..." class="input-dark text-sm">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" name="status" value="approved"
                                class="px-4 py-2 bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold rounded-xl hover:bg-green-500/20 transition-colors inline-flex items-center gap-2">
                            <i class="fa-solid fa-check"></i> Setujui
                        </button>
                        <button type="submit" name="status" value="rejected"
                                class="px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold rounded-xl hover:bg-red-500/20 transition-colors inline-flex items-center gap-2">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                        @if(in_array($booking->status, ['approved','approved_dosen','approved_laboran']))
                        <button type="submit" name="status" value="completed"
                                class="px-4 py-2 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold rounded-xl hover:bg-blue-500/20 transition-colors inline-flex items-center gap-2"
                                onclick="return confirm('Tandai peminjaman ini selesai?')">
                            <i class="fa-solid fa-flag-checkered"></i> Selesaikan
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Riwayat Audit --}}
        @if($booking->auditLogs->isNotEmpty())
        <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 bg-premium-gray/30">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-brand-orange"></i>
                    Riwayat Aktivitas
                </h3>
            </div>
            <div class="divide-y divide-white/5">
                @foreach($booking->auditLogs->sortByDesc('created_at') as $log)
                <div class="p-5 flex items-start gap-4">
                    <div class="w-8 h-8 bg-brand-orange/10 border border-brand-orange/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-rotate text-brand-orange text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-white font-semibold">{{ $log->notes }}</p>
                        @if($log->status_from && $log->status_to)
                        <p class="text-xs text-gray-500 mt-0.5">
                            <span class="text-gray-400">{{ $log->status_from }}</span>
                            <i class="fa-solid fa-arrow-right mx-1 text-[10px]"></i>
                            <span class="text-brand-orange">{{ $log->status_to }}</span>
                        </p>
                        @endif
                        <p class="text-xs text-gray-600 mt-1">{{ $log->created_at->format('d M Y, H:i') }}
                            @if($log->user) · {{ $log->user->name }} @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-app-layout>

