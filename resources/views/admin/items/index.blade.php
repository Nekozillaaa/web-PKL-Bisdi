<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="section-heading">Panel Administrator</p>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Kelola <span class="text-gradient">Barang Laboratorium</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">Laboratorium: <span class="text-brand-orange font-bold">{{ $lab->name ?? 'Lab Bisnis Digital' }}</span></p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('admin.items.create') }}" class="btn-brand inline-flex items-center gap-2 text-sm whitespace-nowrap">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Barang Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/20 text-green-400 px-5 py-4 rounded-2xl animate-fade-in">
                <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card-dark rounded-2xl p-5 border">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-brand-orange/10 border border-brand-orange/20 rounded-xl flex items-center justify-center text-brand-orange">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </div>
                    <span class="text-2xl font-black text-white">{{ $stats['total_items'] }}</span>
                </div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Barang</p>
            </div>

            <div class="card-dark rounded-2xl p-5 border">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
                        <i class="fa-solid fa-cubes text-sm"></i>
                    </div>
                    <span class="text-2xl font-black text-white">{{ $stats['total_units'] }}</span>
                </div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Unit Fisik</p>
            </div>

            <div class="card-dark rounded-2xl p-5 border">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center justify-center text-green-400">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                    </div>
                    <span class="text-2xl font-black text-white">{{ $stats['available_units'] }}</span>
                </div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Siap Dipinjam</p>
            </div>

            <div class="card-dark rounded-2xl p-5 border">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center justify-center text-red-400">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                    <span class="text-2xl font-black text-white">{{ $stats['damaged_units'] }}</span>
                </div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Perlu Perbaikan</p>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <div class="card-dark rounded-2xl p-5 border border-white/7">
            <form method="GET" action="{{ route('admin.items.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode barang..." class="input-dark text-xs">
                </div>
                <div>
                    <select name="condition" class="input-dark text-xs">
                        <option value="">Semua Kondisi</option>
                        <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Kondisi Baik</option>
                        <option value="slightly_damaged" {{ request('condition') == 'slightly_damaged' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-brand-orange text-white font-bold text-xs rounded-xl hover:bg-orange-600 transition-colors inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter text-xs"></i>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'condition']))
                        <a href="{{ route('admin.items.index') }}" class="px-4 py-2.5 bg-white/5 border border-white/10 text-gray-300 font-bold text-xs rounded-xl hover:bg-white/10 transition-colors inline-flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-premium-gray/30">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand-orange"></i>
                    Daftar Barang Inventaris Lab Bisnis Digital
                </h3>
                <span class="text-xs text-gray-500">{{ $items->total() }} barang ditemukan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/5 bg-premium-gray/50">
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Kode</th>
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Nama Barang</th>
                            <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Ketersediaan</th>
                            <th class="py-3.5 px-5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Kondisi</th>
                            <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="py-3.5 px-5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($items as $item)
                        <tr class="hover:bg-white/3 transition-colors group">
                            <td class="py-4 px-5 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-mono font-bold text-brand-orange">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-white text-sm">{{ $item->name }}</div>
                                @if($item->description)
                                    <div class="text-xs text-gray-500 line-clamp-1 max-w-xs mt-0.5">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center whitespace-nowrap">
                                <span class="font-bold {{ $item->available_quantity > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $item->available_quantity }}
                                </span>
                                <span class="text-gray-500 text-xs">/ {{ $item->total_quantity }} unit</span>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($item->condition === 'good')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/10 border border-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Baik
                                    </span>
                                @elseif($item->condition === 'slightly_damaged')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Rusak Ringan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-500/10 border border-red-500/20 text-red-400 rounded-full text-xs font-semibold">
                                        <i class="fa-solid fa-circle-xmark text-[10px]"></i> Rusak Berat
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center whitespace-nowrap">
                                @if($item->is_active)
                                    <span class="text-xs font-semibold text-green-400">Aktif</span>
                                @else
                                    <span class="text-xs font-semibold text-gray-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="p-2 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/20 transition-colors" title="Edit Barang">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg hover:bg-red-500/20 transition-colors" title="Hapus Barang">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="w-14 h-14 bg-premium-gray rounded-2xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-box-open text-gray-600 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm font-medium">Belum ada barang di laboratorium.</p>
                                <a href="{{ route('admin.items.create') }}" class="btn-brand text-xs inline-flex items-center gap-1.5 mt-3">
                                    <i class="fa-solid fa-plus text-xs"></i> Tambah Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="px-6 py-4 border-t border-white/5 bg-premium-gray/20">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
