<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="section-heading">Manajemen Dokumen</p>
                <h2 class="text-2xl font-black text-white leading-tight">
                    Kelola <span class="text-gradient">Dokumen Lab</span>
                </h2>
            </div>
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white/5 border border-white/10 text-gray-300 font-bold text-xs rounded-xl hover:bg-white/10 transition-colors inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-xs"></i> Dashboard
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
            <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/20 text-green-400 px-5 py-4 rounded-2xl">
                <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-start gap-3 bg-brand-red/10 border border-brand-red/20 text-red-400 px-5 py-4 rounded-2xl">
                <i class="fa-solid fa-circle-exclamation text-lg shrink-0 mt-0.5"></i>
                <ul class="text-sm font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Upload Form --}}
        <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 bg-premium-gray/30">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-brand-orange"></i>
                    Unggah Dokumen Baru
                </h3>
            </div>
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label-dark">Judul Dokumen <span class="text-brand-orange">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               placeholder="Contoh: SOP Peminjaman Lab" class="input-dark">
                    </div>
                    <div>
                        <label class="label-dark">File Dokumen <span class="text-brand-orange">*</span></label>
                        <input type="file" name="file" required
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                               class="input-dark text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-orange/10 file:text-brand-orange hover:file:bg-brand-orange/20 cursor-pointer">
                        <p class="text-xs text-gray-600 mt-1">PDF, Word, Excel, PPT, ZIP — Maks. 20MB</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-brand text-sm inline-flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>

        {{-- Documents List --}}
        <div class="card-dark rounded-2xl border border-white/7 overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-brand-orange"></i>
                    Daftar Dokumen
                </h3>
                <span class="text-xs text-gray-500">{{ $documents->count() }} dokumen</span>
            </div>

            @forelse($documents as $doc)
            <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between gap-4 hover:bg-white/2 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    @php
                        $ext = pathinfo($doc->filename, PATHINFO_EXTENSION);
                        $iconMap = [
                            'pdf'  => 'fa-file-pdf text-red-400',
                            'doc'  => 'fa-file-word text-blue-400',
                            'docx' => 'fa-file-word text-blue-400',
                            'xls'  => 'fa-file-excel text-green-400',
                            'xlsx' => 'fa-file-excel text-green-400',
                            'ppt'  => 'fa-file-powerpoint text-orange-400',
                            'pptx' => 'fa-file-powerpoint text-orange-400',
                            'zip'  => 'fa-file-zipper text-yellow-400',
                            'rar'  => 'fa-file-zipper text-yellow-400',
                        ];
                        $fileIcon = $iconMap[strtolower($ext)] ?? 'fa-file text-gray-400';
                    @endphp
                    <div class="w-10 h-10 bg-white/5 border border-white/10 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $fileIcon }} text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white font-semibold text-sm truncate">{{ $doc->title }}</p>
                        <p class="text-gray-600 text-xs truncate">{{ $doc->filename }}
                            @if($doc->file_size)
                                · {{ number_format($doc->file_size / 1024, 1) }} KB
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('documents.view', $doc->id) }}" target="_blank"
                       class="px-3 py-1.5 bg-white/5 border border-white/10 text-gray-300 text-xs font-bold rounded-lg hover:bg-white/10 transition-colors inline-flex items-center gap-1">
                        <i class="fa-solid fa-eye text-xs"></i> Lihat
                    </a>
                    <a href="{{ route('documents.download', $doc->id) }}"
                       class="px-3 py-1.5 bg-brand-orange/10 border border-brand-orange/20 text-brand-orange text-xs font-bold rounded-lg hover:bg-brand-orange/20 transition-colors inline-flex items-center gap-1">
                        <i class="fa-solid fa-download text-xs"></i> Unduh
                    </a>
                    <form action="{{ route('admin.documents.destroy', $doc->id) }}" method="POST"
                          onsubmit="return confirm('Hapus dokumen ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold rounded-lg hover:bg-red-500/20 transition-colors inline-flex items-center gap-1">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="py-12 text-center">
                <div class="w-14 h-14 bg-premium-gray rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-folder-open text-gray-600 text-2xl"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">Belum ada dokumen. Unggah dokumen pertama!</p>
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>

