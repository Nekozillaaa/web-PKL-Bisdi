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
                <ul class="text-sm font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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
                        <p class="text-gray-500 text-xs mt-0.5">Isi semua field dengan benar</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Pilih Laboratorium -->
                    <div>
                        <label class="label-dark" for="laboratory_id">
                            <i class="fa-solid fa-microscope mr-2 text-brand-orange"></i>Pilih Laboratorium
                        </label>
                        <select name="laboratory_id" id="laboratory_id"
                                class="input-dark" required>
                            <option value="" class="bg-premium-dark">— Pilih Laboratorium —</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}"
                                        class="bg-premium-dark"
                                        {{ old('laboratory_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }} (Kapasitas: {{ $lab->capacity }} orang)
                                </option>
                            @endforeach
                        </select>
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

                    <!-- Tujuan Peminjaman -->
                    <div>
                        <label class="label-dark" for="purpose">
                            <i class="fa-solid fa-file-lines mr-2 text-brand-orange"></i>Tujuan Peminjaman & Kebutuhan Alat
                        </label>
                        <textarea name="purpose"
                                  id="purpose"
                                  rows="5"
                                  class="input-dark resize-none"
                                  required
                                  placeholder="Jelaskan tujuan peminjaman dan sebutkan alat-alat yang akan digunakan...">{{ old('purpose') }}</textarea>
                        <p class="text-xs text-gray-600 mt-2">
                            <i class="fa-solid fa-circle-info mr-1.5 text-brand-orange/60"></i>
                            Permohonan akan diproses oleh Dosen lalu Laboran.
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
