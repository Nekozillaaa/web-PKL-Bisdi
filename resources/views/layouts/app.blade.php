<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lab Booking') }} — Sistem Peminjaman Laboratorium</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-premium-black text-gray-100"
          x-data="{ docOpen: false, previewOpen: false, previewUrl: '', previewTitle: '', downloadUrl: '' }">
        <div class="min-h-screen bg-premium-black flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="page-header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-10 flex-1">
                {{ $slot }}
            </main>
        </div>

        <!-- Floating Document Button (Pojok Kanan Bawah) -->
        @php
            $publicDocuments = \App\Models\LabDocument::where('is_active', true)->latest()->get();
        @endphp
        <div class="fixed bottom-6 right-6 z-50">
            <!-- Floating Button -->
            <button @click="docOpen = !docOpen"
                    class="w-14 h-14 bg-gradient-brand rounded-full shadow-2xl shadow-orange-500/40 flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 relative group"
                    title="Dokumen Lab">
                <i class="fa-solid fa-file-lines text-xl" x-show="!docOpen"></i>
                <i class="fa-solid fa-xmark text-xl" x-show="docOpen" x-cloak></i>
                @if($publicDocuments->isNotEmpty())
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-brand-red rounded-full text-[10px] font-black flex items-center justify-center shadow">
                        {{ $publicDocuments->count() }}
                    </span>
                @endif
                <span class="absolute right-16 bg-premium-dark border border-white/10 text-white text-xs font-bold px-3 py-1.5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-xl">
                    Dokumen & Template Lab
                </span>
            </button>

            <!-- Document List Panel -->
            <div x-show="docOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 x-cloak
                 @click.outside="docOpen = false"
                 class="absolute bottom-16 right-0 w-84 sm:w-96 bg-premium-dark border border-white/10 rounded-2xl shadow-2xl shadow-black/90 overflow-hidden z-50">

                <!-- Header -->
                <div class="px-5 py-4 border-b border-white/5 bg-premium-gray/40">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-brand rounded-xl flex items-center justify-center shadow">
                            <i class="fa-solid fa-folder-open text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-bold text-white text-sm">Dokumen & Template Lab</p>
                            <p class="text-gray-500 text-xs">Unduh atau preview formulir & panduan</p>
                        </div>
                    </div>
                </div>

                <!-- Document List -->
                <div class="max-h-72 overflow-y-auto divide-y divide-white/5">
                    @forelse($publicDocuments as $doc)
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
                        ];
                        $fileIcon = $iconMap[strtolower($ext)] ?? 'fa-file text-gray-400';
                        $viewUrl = route('documents.view', $doc->id);
                        $downUrl = route('documents.download', $doc->id);
                    @endphp
                    <div class="flex items-center justify-between gap-3 px-5 py-3 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-9 h-9 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $fileIcon }} text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-white text-sm font-semibold truncate">{{ $doc->title }}</p>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">{{ strtoupper($ext) }}
                                    @if($doc->file_size) · {{ number_format($doc->file_size / 1024, 1) }} KB @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button"
                                    @click="previewUrl = '{{ $viewUrl }}'; previewTitle = '{{ addslashes($doc->title) }}'; downloadUrl = '{{ $downUrl }}'; previewOpen = true; docOpen = false;"
                                    class="p-2 bg-white/5 border border-white/10 text-gray-300 hover:text-white rounded-lg hover:bg-white/10 transition-colors text-xs"
                                    title="Embed / Preview Dokumen">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </button>
                            <a href="{{ $downUrl }}"
                               class="p-2 bg-brand-orange/10 border border-brand-orange/20 text-brand-orange rounded-lg hover:bg-brand-orange/20 transition-colors text-xs"
                               title="Unduh Dokumen">
                                <i class="fa-solid fa-download text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center">
                        <i class="fa-solid fa-folder-open text-gray-600 text-2xl mb-2"></i>
                        <p class="text-xs text-gray-500">Belum ada dokumen yang diunggah.</p>
                    </div>
                    @endforelse
                </div>

                @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="px-5 py-3 border-t border-white/5 bg-white/2 flex items-center justify-between">
                    <a href="{{ route('admin.documents.index') }}" class="text-xs font-bold text-brand-orange hover:underline inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-cog text-xs"></i> Kelola Dokumen (Admin)
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Embedded Document Modal (Preview / Embed Viewer) -->
        <div x-show="previewOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div @click.outside="previewOpen = false"
                 class="w-full max-w-4xl bg-premium-dark border border-white/10 rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between bg-premium-gray/40">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-brand-orange/10 rounded-lg flex items-center justify-center text-brand-orange">
                            <i class="fa-solid fa-file-lines text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm" x-text="previewTitle"></h3>
                            <p class="text-xs text-gray-500">Embedded Document Viewer</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="downloadUrl"
                           class="px-3 py-1.5 bg-brand-orange text-white text-xs font-bold rounded-lg hover:bg-orange-600 transition-colors inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-download text-xs"></i> Unduh
                        </a>
                        <button type="button" @click="previewOpen = false" class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body: Embedded iframe -->
                <div class="flex-1 p-4 bg-black/40 overflow-hidden">
                    <iframe :src="previewUrl"
                            class="w-full h-[65vh] rounded-xl border border-white/10 bg-white"></iframe>
                </div>
            </div>
        </div>
    </body>
</html>
