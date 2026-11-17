<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="section-heading">Selamat datang, {{ auth()->user()->name }}</p>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Dashboard <span class="text-gradient">Peminjaman</span>
                </h2>
            </div>
            @if(auth()->user()->role == 'mahasiswa')
                <div class="shrink-0">
                    <a href="{{ route('bookings.create') }}"
                       class="btn-brand inline-flex items-center gap-2 text-sm whitespace-nowrap">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Ajukan Baru
                    </a>
                </div>
            @endif
            @if(auth()->user()->role == 'admin')
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('admin.items.index') }}"
                       class="px-4 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 text-white font-bold rounded-full text-xs inline-flex items-center gap-2 transition-colors whitespace-nowrap">
                        <i class="fa-solid fa-boxes-stacked text-brand-orange text-xs"></i>
                        Kelola Barang Lab
                    </a>
                    <a href="{{ route('admin.documents.index') }}"
                       class="px-4 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 text-white font-bold rounded-full text-xs inline-flex items-center gap-2 transition-colors whitespace-nowrap">
                        <i class="fa-solid fa-folder-open text-brand-orange text-xs"></i>
                        Kelola Dokumen
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/20 text-green-400 px-5 py-4 rounded-2xl animate-fade-in">
                <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-center gap-3 bg-brand-red/10 border border-brand-red/20 text-red-400 px-5 py-4 rounded-2xl animate-fade-in">
                <i class="fa-solid fa-circle-exclamation text-lg shrink-0"></i>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Status Overview Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $statuses = [
                    ['label' => 'Pending',   'status' => 'pending',   'icon' => 'fa-clock',          'color' => 'text-yellow-400', 'bg' => 'bg-yellow-400/10 border-yellow-400/20'],
                    ['label' => 'Disetujui', 'status' => 'approved',  'icon' => 'fa-circle-check',   'color' => 'text-green-400',  'bg' => 'bg-green-400/10 border-green-400/20'],
                    ['label' => 'Selesai',   'status' => 'completed', 'icon' => 'fa-flag-checkered', 'color' => 'text-blue-400',   'bg' => 'bg-blue-400/10 border-blue-400/20'],
                    ['label' => 'Ditolak',   'status' => 'rejected',  'icon' => 'fa-circle-xmark',   'color' => 'text-red-400',    'bg' => 'bg-red-400/10 border-red-400/20'],
                ];
            @endphp
            @foreach($statuses as $stat)
            <div class="card-dark rounded-2xl p-5 border">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 {{ $stat['bg'] }} border rounded-xl flex items-center justify-center {{ $stat['color'] }}">
                        <i class="fa-solid {{ $stat['icon'] }} text-sm"></i>
                    </div>
                    <span class="text-2xl font-black text-white">
                        {{ $bookings->where('status', $stat['status'])->count() }}
                    </span>
                </div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Filter Bar (Admin only) --}}
        @if(auth()->user()->role === 'admin')
        <div class="card-dark rounded-2xl border border-white/7 p-5">
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[180px]">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Cari Pemohon / Tujuan</label>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama, NIM, atau tujuan..."
                               class="input-dark pl-9 text-sm py-2.5">
                    </div>
                </div>
                <div class="min-w-[160px]">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Status</label>
                    <select name="status" class="input-dark text-sm py-2.5">
                        <option value="">— Semua Status —</option>
                        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>Disetujui</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="min-w-[150px]">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Tanggal Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-dark text-sm py-2.5">
                </div>
                <div class="min-w-[150px]">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Tanggal Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-dark text-sm py-2.5">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2.5 bg-brand-orange text-white text-xs font-bold rounded-xl hover:bg-orange-600 transition-colors inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/5 border border-white/10 text-gray-400 text-xs font-bold rounded-xl hover:bg-white/10 transition-colors inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
        @endif

        {{-- Bookings Table --}}
        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand-orange"></i>
                    Daftar Peminjaman
                </h3>
                <span class="text-xs text-gray-500">{{ $bookings->count() }} total data</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 bg-premium-gray/50">
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Laboratorium</th>
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Pemohon</th>
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($bookings as $booking)
                        @php
                            $statusConfig = [
                                'pending'          => ['bg' => 'bg-yellow-400/10 border-yellow-400/20 text-yellow-300', 'label' => 'Menunggu'],
                                'approved'         => ['bg' => 'bg-green-400/10 border-green-400/20 text-green-300',   'label' => 'Disetujui'],
                                'approved_dosen'   => ['bg' => 'bg-green-400/10 border-green-400/20 text-green-300',   'label' => 'Disetujui'],
                                'approved_laboran' => ['bg' => 'bg-green-400/10 border-green-400/20 text-green-300',   'label' => 'Disetujui'],
                                'completed'        => ['bg' => 'bg-blue-400/10 border-blue-400/20 text-blue-300',      'label' => 'Selesai'],
                                'rejected'         => ['bg' => 'bg-red-400/10 border-red-400/20 text-red-300',         'label' => 'Ditolak'],
                            ];
                            $sc = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-500/10 border-gray-500/20 text-gray-400', 'label' => $booking->status];
                        @endphp
                        <tr class="hover:bg-white/3 transition-colors group">
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-brand-orange/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-flask text-brand-orange text-xs"></i>
                                    </div>
                                    <span class="font-semibold text-gray-200">{{ $booking->laboratory->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                <p class="text-gray-300 font-medium">{{ $booking->user->name }}</p>
                                <p class="text-gray-600 text-xs">{{ $booking->user->nim_nip }}</p>
                                <p class="text-gray-400 text-xs truncate max-w-xs mt-1" title="{{ $booking->purpose }}">
                                    <i class="fa-solid fa-file-lines text-[10px] text-brand-orange mr-1"></i>{{ $booking->purpose }}
                                </p>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                <div class="text-gray-300 text-xs font-medium">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('d M Y') }}
                                </div>
                                <div class="text-gray-500 text-xs mt-0.5">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full border text-[11px] font-bold uppercase tracking-wide whitespace-nowrap {{ $sc['bg'] }}">
                                    {{ $sc['label'] }}
                                </span>
                                @if($booking->attachment_path)
                                    <span class="ml-1 inline-flex items-center px-2 py-1 rounded-full bg-white/5 border border-white/10 text-gray-500 text-[10px]" title="Ada lampiran">
                                        <i class="fa-solid fa-paperclip"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                    {{-- Detail Button for all --}}
                                    <a href="{{ route('bookings.show', $booking->id) }}"
                                       class="px-3 py-1.5 bg-white/5 border border-white/10 text-gray-300 text-xs font-bold rounded-lg hover:bg-white/10 transition-colors whitespace-nowrap">
                                        <i class="fa-solid fa-eye mr-1"></i>Detail
                                    </a>

                                    {{-- Admin Quick Actions --}}
                                    @if(auth()->user()->role === 'admin')
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="inline-block m-0">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1.5 bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold rounded-lg hover:bg-green-500/20 transition-colors whitespace-nowrap">
                                                    <i class="fa-solid fa-check mr-1"></i>Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="inline-block m-0">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1.5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold rounded-lg hover:bg-red-500/20 transition-colors whitespace-nowrap">
                                                    <i class="fa-solid fa-xmark mr-1"></i>Tolak
                                                </button>
                                            </form>
                                        @elseif(in_array($booking->status, ['approved', 'approved_dosen', 'approved_laboran']))
                                            <form action="{{ route('bookings.status', $booking->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Tandai peminjaman ini selesai?');">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="px-3 py-1.5 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold rounded-lg hover:bg-blue-500/20 transition-colors whitespace-nowrap">
                                                    <i class="fa-solid fa-flag-checkered mr-1"></i>Selesai
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- Mahasiswa --}}
                                    @if(auth()->user()->role === 'mahasiswa')
                                        @if(in_array($booking->status, ['pending', 'approved', 'approved_dosen', 'approved_laboran']))
                                            <span class="text-[11px] text-gray-500 italic whitespace-nowrap">Sedang Diproses</span>
                                        @elseif($booking->status === 'completed')
                                            <span class="text-[11px] text-green-400 font-semibold whitespace-nowrap"><i class="fa-solid fa-check mr-1"></i>Selesai</span>
                                        @elseif($booking->status === 'rejected')
                                            <span class="text-[11px] text-red-400 font-semibold whitespace-nowrap"><i class="fa-solid fa-ban mr-1"></i>Ditolak</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 bg-premium-gray rounded-2xl flex items-center justify-center">
                                        <i class="fa-solid fa-inbox text-gray-600 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium">Belum ada data peminjaman.</p>
                                    @if(auth()->user()->role === 'mahasiswa')
                                        <a href="{{ route('bookings.create') }}" class="btn-brand text-sm mt-1">
                                            <i class="fa-solid fa-plus text-xs mr-1.5"></i>Ajukan Sekarang
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
