<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="section-heading">Manajemen Barang</p>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Edit <span class="text-gradient">Barang Laboratorium</span>
                </h2>
            </div>
            <a href="{{ route('admin.items.index') }}" class="px-4 py-2 bg-white/5 border border-white/10 text-gray-300 font-bold text-xs rounded-xl hover:bg-white/10 transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="flex items-start gap-3 bg-brand-red/10 border border-brand-red/20 text-red-400 px-5 py-4 rounded-2xl mb-6">
                <i class="fa-solid fa-circle-exclamation text-lg shrink-0 mt-0.5"></i>
                <ul class="text-sm font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <div class="px-8 py-6 border-b border-white/5 bg-premium-gray/30">
                <h3 class="font-bold text-white text-base flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-brand-orange"></i>
                    Perbarui Data Barang: {{ $item->name }}
                </h3>
                <p class="text-gray-500 text-xs mt-0.5">Perbarui informasi ketersediaan atau kondisi fisik barang</p>
            </div>

            <form action="{{ route('admin.items.update', $item->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="label-dark">Nama Barang <span class="text-brand-orange">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required class="input-dark">
                    </div>

                    <div>
                        <label for="code" class="label-dark">Kode / No. Inventaris <span class="text-brand-orange">*</span></label>
                        <input type="text" id="code" name="code" value="{{ old('code', $item->code) }}" required class="input-dark font-mono uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="laboratory_id" class="label-dark">Laboratorium Penyimpan <span class="text-brand-orange">*</span></label>
                        <select id="laboratory_id" name="laboratory_id" required class="input-dark">
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ old('laboratory_id', $item->laboratory_id) == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="condition" class="label-dark">Kondisi Fisik <span class="text-brand-orange">*</span></label>
                        <select id="condition" name="condition" required class="input-dark">
                            <option value="good" {{ old('condition', $item->condition) == 'good' ? 'selected' : '' }}>Baik (Normal & Siap Pakai)</option>
                            <option value="slightly_damaged" {{ old('condition', $item->condition) == 'slightly_damaged' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="damaged" {{ old('condition', $item->condition) == 'damaged' ? 'selected' : '' }}>Rusak Berat (Perlu Perbaikan)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="total_quantity" class="label-dark">Total Unit Keseluruhan <span class="text-brand-orange">*</span></label>
                        <input type="number" id="total_quantity" name="total_quantity" value="{{ old('total_quantity', $item->total_quantity) }}" min="1" required class="input-dark">
                    </div>

                    <div>
                        <label for="available_quantity" class="label-dark">Unit Siap Dipinjam <span class="text-brand-orange">*</span></label>
                        <input type="number" id="available_quantity" name="available_quantity" value="{{ old('available_quantity', $item->available_quantity) }}" min="0" required class="input-dark">
                    </div>
                </div>

                <div>
                    <label for="description" class="label-dark">Spesifikasi / Catatan Tambahan</label>
                    <textarea id="description" name="description" rows="3" class="input-dark">{{ old('description', $item->description) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="rounded border-gray-700 text-brand-orange focus:ring-brand-orange bg-premium-gray">
                    <label for="is_active" class="text-sm font-medium text-gray-300">
                        Status Aktif (Barang dapat diajukan untuk peminjaman)
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/5">
                    <a href="{{ route('admin.items.index') }}" class="px-5 py-2.5 bg-white/5 text-gray-300 rounded-full font-bold text-sm hover:bg-white/10 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="btn-brand text-sm inline-flex items-center gap-2">
                        <i class="fa-solid fa-check text-xs"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

