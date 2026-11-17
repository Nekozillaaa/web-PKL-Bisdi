<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-heading">Form Baru</p>
            <h2 class="text-2xl font-black text-white leading-tight">
                Ajukan <span class="text-gradient">Peminjaman</span> Laboratorium
            </h2>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($errors->any())
            <div class="flex items-start gap-3 bg-brand-red/10 border border-brand-red/20 text-red-400 px-5 py-4 rounded-2xl mb-6">
                <i class="fa-solid fa-circle-exclamation text-lg shrink-0 mt-0.5"></i>
                <div class="space-y-1">
                    <p class="font-bold text-sm">Notifikasi Kesalahan / Invalid Input:</p>
                    <ul class="text-sm font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-white/5 bg-premium-gray/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-brand rounded-xl flex items-center justify-center shadow-lg shadow-brand-orange/20">
                        <i class="fa-solid fa-flask text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm">Form Peminjaman</h3>
                        @if($lab)
                            <p class="text-gray-500 text-xs mt-0.5">{{ $lab->name }} — Kapasitas {{ $lab->capacity }} orang</p>
                        @else
                            <p class="text-gray-500 text-xs mt-0.5">Isi semua field dengan benar</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    @if($lab)
                        <input type="hidden" name="laboratory_id" value="{{ $lab->id }}">
                    @endif

                    <!-- Info Lab -->
                    <div class="flex items-center gap-3 p-4 bg-brand-orange/5 border border-brand-orange/20 rounded-xl">
                        <div class="w-9 h-9 bg-brand-orange/10 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-microscope text-brand-orange text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Laboratorium</p>
                            <p class="text-white font-bold text-sm">{{ $lab->name ?? 'Lab Bisnis Digital' }}</p>
                        </div>
                    </div>

                    <!-- Waktu Mulai & Selesai -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="label-dark" for="start_time">
                                <i class="fa-regular fa-clock mr-2 text-brand-orange"></i>Waktu Mulai
                            </label>
                            <input type="datetime-local"
                                   name="start_time"
                                   id="start_time"
                                   value="{{ old('start_time') }}"
                                   class="input-dark" required>
                        </div>
                        <div>
                            <label class="label-dark" for="end_time">
                                <i class="fa-regular fa-clock mr-2 text-brand-orange"></i>Waktu Selesai
                            </label>
                            <input type="datetime-local"
                                   name="end_time"
                                   id="end_time"
                                   value="{{ old('end_time') }}"
                                   class="input-dark" required>
                        </div>
                    </div>

                    <!-- Pilih Perangkat Lab (Inventaris) -->
                    @if(isset($items) && $items->isNotEmpty())
                    <div class="space-y-3">
                        <label class="label-dark flex items-center justify-between">
                            <span><i class="fa-solid fa-boxes-stacked mr-2 text-brand-orange"></i>Pinjam Perangkat / Alat Lab</span>
                            <span class="text-gray-500 font-normal text-xs">(Pilih & atur jumlah unit yang dibutuhkan)</span>
                        </label>
                        <div class="divide-y divide-white/5 border border-white/10 rounded-xl bg-premium-gray/30 p-2 max-h-60 overflow-y-auto">
                            @foreach($items as $item)
                            <div class="flex items-center justify-between gap-4 p-3 hover:bg-white/3 rounded-lg transition-colors">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-white truncate">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $item->code }} · Total {{ $item->total_quantity }} unit</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <label class="text-xs text-gray-400">Jml:</label>
                                    <input type="number"
                                           name="items[{{ $item->id }}]"
                                           min="0"
                                           max="{{ $item->total_quantity }}"
                                           value="{{ old('items.'.$item->id, 0) }}"
                                           class="w-16 bg-premium-dark border border-white/10 text-white rounded-lg px-2 py-1 text-center text-xs focus:ring-1 focus:ring-brand-orange">
                                    <span class="text-xs text-gray-500">unit</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Tujuan Peminjaman -->
                    <div>
                        <label class="label-dark" for="purpose">
                            <i class="fa-solid fa-file-lines mr-2 text-brand-orange"></i>Tujuan Peminjaman & Kebutuhan Tambahan
                        </label>
                        <textarea name="purpose"
                                  id="purpose"
                                  rows="4"
                                  class="input-dark resize-none"
                                  required
                                  placeholder="Jelaskan tujuan peminjaman dan rincian kegiatan...">{{ old('purpose') }}</textarea>
                    </div>

                    <!-- Upload Dokumen Pendukung Sebelum Pengajuan -->
                    <div x-data="{ fileName: null, dragOver: false }">
                        <label class="label-dark" for="attachment">
                            <i class="fa-solid fa-paperclip mr-2 text-brand-orange"></i>Unggah Dokumen Pengajuan
                            <span class="text-gray-500 font-normal ml-1">(Surat Pengantar / Proposal / Bukti Izin)</span>
                        </label>
                        <div
                            class="relative border-2 border-dashed rounded-xl p-6 text-center transition-colors cursor-pointer"
                            :class="dragOver ? 'border-brand-orange bg-brand-orange/5' : 'border-white/10 hover:border-white/20'"
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="
                                dragOver = false;
                                const f = $event.dataTransfer.files[0];
                                if(f){ fileName = f.name; $refs.fileInput.files = $event.dataTransfer.files; }
                            "
                            @click="$refs.fileInput.click()"
                        >
                            <input type="file"
                                   id="attachment"
                                   name="attachment"
                                   x-ref="fileInput"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png"
                                   class="hidden"
                                   @change="fileName = $event.target.files[0]?.name">

                            <template x-if="!fileName">
                                <div class="space-y-2">
                                    <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mx-auto">
                                        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-400 font-medium">Klik atau seret dokumen ke sini sebelum mengajukan</p>
                                    <p class="text-xs text-gray-600">Format yang didukung: PDF, Word, Excel, PPT, ZIP, Gambar (Maks. 10MB)</p>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="w-10 h-10 bg-brand-orange/10 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-file-circle-check text-brand-orange"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm text-white font-semibold" x-text="fileName"></p>
                                        <p class="text-xs text-gray-500">Klik untuk mengganti file</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">
                            <i class="fa-solid fa-circle-info mr-1.5 text-brand-orange/60"></i>
                            Anda dapat mengunduh template surat pengajuan melalui ikon dokumen di pojok kiri bawah.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-arrow-left text-xs"></i>Kembali
                        </a>
                        <button type="submit" class="btn-brand">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
