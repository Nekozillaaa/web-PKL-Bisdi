<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Lab Bisnis Digital</title>
    <meta name="description" content="Platform peminjaman Lab Bisnis Digital yang mudah, cepat, dan transparan.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CDN (standalone for welcome page) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        premium: { black: '#0a0a0a', dark: '#171717', gray: '#262626' },
                        brand: { orange: '#ff6b00', red: '#dc2626' }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(24px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0a0a0a; }
        ::selection { background-color: #ff6b00; color: white; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #171717; }
        ::-webkit-scrollbar-thumb { background: #ff6b00; border-radius: 4px; }
        .glass-dark { background: rgba(10,10,10,0.88); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .text-gradient { background: linear-gradient(135deg, #ff6b00, #dc2626); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-gradient-brand { background: linear-gradient(135deg, #ff6b00, #dc2626); }
        .card-dark { background: #171717; border: 1px solid rgba(255,255,255,0.07); }
        .input-field { background: #262626; border: 1px solid rgba(255,255,255,0.1); color: #e5e7eb; border-radius: 0.75rem; padding: 0.625rem 1rem; font-size: 0.875rem; width: 100%; outline: none; transition: border-color 0.2s; }
        .input-field:focus { border-color: #ff6b00; }
        .input-field::placeholder { color: #4b5563; }
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="text-gray-100 antialiased">

    <!-- NAVBAR -->
    <nav x-data="{ scrolled: false, open: false }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'glass-dark py-3': scrolled || open, 'bg-transparent py-5': !scrolled && !open }"
         class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 bg-gradient-brand rounded-xl flex items-center justify-center shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-flask text-white text-sm"></i>
                    </div>
                    <div>
                        <span class="font-black text-white text-base">Lab</span><span class="font-black text-gradient text-base">Booking</span>
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest">Bisnis Digital</p>
                    </div>
                </a>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="#logbook" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-clipboard-list mr-1.5 text-xs"></i>Absensi
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
                            <i class="fa-solid fa-gauge-high mr-1.5 text-xs"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-brand text-white text-sm font-bold rounded-full hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5 transition-all duration-300">Daftar Sekarang</a>
                    @endauth
                </div>

                <!-- Mobile Hamburger -->
                <button @click="open = !open" class="md:hidden text-gray-400 hover:text-white p-2">
                    <i class="fa-solid text-xl" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" x-cloak class="md:hidden mt-4 pb-4 space-y-2 border-t border-white/10 pt-4">
                <a href="#logbook" class="block py-2.5 text-sm text-gray-400 hover:text-white transition-colors">Absensi</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="block py-2.5 text-sm text-gray-400 hover:text-white transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block py-2.5 text-sm text-gray-400 hover:text-white transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="block py-2.5 text-sm font-bold text-brand-orange hover:text-white transition-colors">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative min-h-screen flex items-center bg-premium-black overflow-hidden">
        <!-- Background Blobs -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-brand-orange rounded-full blur-[150px] opacity-10 animate-pulse-slow"></div>
            <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-brand-red rounded-full blur-[120px] opacity-10 animate-pulse-slow"></div>
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="max-w-3xl animate-fade-in-up">
                <span class="inline-block py-1.5 px-4 rounded-full border border-brand-orange/40 text-brand-orange text-xs font-bold tracking-widest uppercase mb-6 bg-brand-orange/5">
                    Platform Resmi Kampus
                </span>
                <h1 class="text-5xl md:text-7xl font-black text-white leading-[1.05] mb-6">
                    Sistem <span class="text-gradient">Peminjaman</span><br>Lab Bisnis Digital
                </h1>
                <p class="text-lg md:text-xl text-gray-400 font-light max-w-2xl leading-relaxed mb-10">
                    Kelola peminjaman laboratorium dengan mudah, transparan, dan realtime.
                    Tidak ada bentrok jadwal, semua tercatat rapi dalam satu sistem.
                </p>

                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('bookings.create') }}" class="px-8 py-4 bg-gradient-brand text-white font-bold rounded-full hover:shadow-xl hover:shadow-orange-500/30 hover:-translate-y-1 transition-all duration-300">
                            <i class="fa-solid fa-plus mr-2"></i>Ajukan Peminjaman
                        </a>
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 border border-white/20 text-white font-bold rounded-full hover:border-white/40 hover:bg-white/5 transition-all duration-300">
                            <i class="fa-solid fa-gauge-high mr-2"></i>Lihat Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-brand text-white font-bold rounded-full hover:shadow-xl hover:shadow-orange-500/30 hover:-translate-y-1 transition-all duration-300">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i>Ajukan Peminjaman
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-4 border border-white/20 text-white font-bold rounded-full hover:border-white/40 hover:bg-white/5 transition-all duration-300">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mt-16 max-w-lg">
                <div class="text-center">
                    <div class="text-3xl font-black text-gradient">1</div>
                    <div class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-wider">Laboratorium</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-3xl font-black text-gradient">{{ $schedules->count() }}</div>
                    <div class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-wider">Aktif</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-gradient">24/7</div>
                    <div class="text-xs text-gray-500 font-medium mt-1 uppercase tracking-wider">Online</div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-gray-600">
            <span class="text-xs uppercase tracking-widest">Scroll</span>
            <div class="w-px h-8 bg-gradient-to-b from-gray-600 to-transparent"></div>
        </div>
    </section>

    <!-- DETAIL LAB -->
    @if($laboratory)
    <section class="py-16 bg-premium-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-brand-orange font-bold tracking-widest uppercase text-xs mb-3">Fasilitas Kampus</p>
                <h2 class="text-4xl font-black text-white">Lab Bisnis Digital</h2>
                <div class="w-16 h-1 bg-gradient-brand mx-auto mt-5 rounded-full"></div>
            </div>

            <div class="max-w-2xl mx-auto card-dark rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-brand-orange/10 border border-brand-orange/20 rounded-2xl flex items-center justify-center text-brand-orange text-3xl mx-auto mb-5">
                    <i class="fa-solid fa-microscope"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">{{ $laboratory->name }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">{{ $laboratory->description }}</p>
                <div class="flex items-center justify-center gap-4">
                    <span class="px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-gray-400 text-xs font-medium">
                        <i class="fa-solid fa-users mr-1.5 text-brand-orange"></i>{{ $laboratory->capacity }} Orang
                    </span>
                    <span class="px-4 py-1.5 bg-green-500/10 border border-green-500/20 rounded-full text-green-400 text-xs font-medium">
                        <i class="fa-solid fa-circle text-xs mr-1.5"></i>Aktif
                    </span>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- JADWAL AKTIF -->
    <section class="py-24 bg-premium-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12">
                <p class="text-brand-orange font-bold tracking-widest uppercase text-xs mb-3">Jadwal Terkini</p>
                <h2 class="text-4xl font-black text-white">Sedang & Akan Berlangsung</h2>
                <div class="w-16 h-1 bg-gradient-brand mt-5 rounded-full"></div>
            </div>

            @if($schedules->isEmpty())
                <div class="card-dark rounded-2xl p-10 text-center">
                    <div class="w-16 h-16 bg-premium-gray rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-calendar-check text-gray-500 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada jadwal peminjaman aktif saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($schedules as $schedule)
                    <div class="card-dark rounded-2xl p-6 flex items-start gap-4 hover:border-white/15 transition-colors">
                        <div class="w-10 h-10 bg-green-500/10 border border-green-500/20 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-circle text-green-400 text-xs animate-pulse"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="font-bold text-white text-sm">{{ $schedule->laboratory->name }}</h3>
                                <span class="px-2 py-0.5 bg-green-500/10 border border-green-500/20 text-green-400 text-[10px] font-bold uppercase rounded-full shrink-0">Booked</span>
                            </div>
                            <p class="text-gray-500 text-xs mb-2">{{ $schedule->user->name }} — {{ $schedule->purpose }}</p>
                            <p class="text-gray-600 text-xs">
                                <i class="fa-regular fa-clock mr-1.5 text-brand-orange/70"></i>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('d M Y, H:i') }} – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- LOGBOOK ABSENSI -->
    <section id="logbook" class="py-24 bg-premium-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-brand-orange font-bold tracking-widest uppercase text-xs mb-3">Logbook Harian</p>
                <h2 class="text-4xl font-black text-white">Absensi Masuk Lab</h2>
                <p class="text-gray-500 mt-3 text-sm">Daftarkan kehadiranmu sebelum memasuki Lab Bisnis Digital</p>
                <div class="w-16 h-1 bg-gradient-brand mx-auto mt-5 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Form Absensi -->
                <div class="card-dark rounded-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-white/5 bg-premium-gray/40">
                        <h3 class="font-bold text-white text-sm flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-brand-orange"></i>
                            Form Absensi
                        </h3>
                        <p class="text-gray-500 text-xs mt-0.5">Isi data diri sebelum masuk laboratorium</p>
                    </div>

                    @if(session('logbook_success'))
                        <div class="mx-6 mt-5 flex items-center gap-3 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm font-medium">
                            <i class="fa-solid fa-circle-check shrink-0"></i>
                            {{ session('logbook_success') }}
                        </div>
                    @endif

                    <form action="{{ route('logbook.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Nama Lengkap <span class="text-brand-orange">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="Masukkan nama lengkap Anda"
                                   class="input-field @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                NIM / NIP <span class="text-gray-600 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" name="nim" value="{{ old('nim') }}"
                                   placeholder="Nomor induk mahasiswa/pegawai"
                                   class="input-field">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                Keperluan / Kegiatan <span class="text-brand-orange">*</span>
                            </label>
                            <input type="text" name="purpose" value="{{ old('purpose') }}" required
                                   placeholder="Contoh: Praktikum E-Commerce, Kerja Tugas Akhir"
                                   class="input-field @error('purpose') border-red-500 @enderror">
                            @error('purpose')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full py-3 bg-gradient-brand text-white font-bold rounded-xl hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5 transition-all duration-300 text-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-clipboard-check"></i>
                            Daftarkan Kehadiran
                        </button>
                    </form>
                </div>

                <!-- Daftar Hadir Hari Ini -->
                <div class="card-dark rounded-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-white/5 bg-premium-gray/40 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-white text-sm flex items-center gap-2">
                                <i class="fa-solid fa-users text-brand-orange"></i>
                                Hadir Hari Ini
                            </h3>
                            <p class="text-gray-500 text-xs mt-0.5">{{ \Carbon\Carbon::today()->format('d F Y') }}</p>
                        </div>
                        <span class="px-3 py-1 bg-brand-orange/10 border border-brand-orange/20 text-brand-orange text-xs font-bold rounded-full">
                            {{ $todayLogbooks->count() }} orang
                        </span>
                    </div>

                    <div class="max-h-80 overflow-y-auto divide-y divide-white/5">
                        @forelse($todayLogbooks as $log)
                        <div class="px-6 py-4 flex items-center gap-3 hover:bg-white/2 transition-colors">
                            <div class="w-9 h-9 bg-gradient-brand rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($log->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm font-semibold truncate">{{ $log->name }}</p>
                                <p class="text-gray-500 text-xs truncate">{{ $log->purpose }}</p>
                                @if($log->nim)
                                    <p class="text-gray-600 text-xs">NIM: {{ $log->nim }}</p>
                                @endif
                            </div>
                            <span class="text-gray-600 text-xs whitespace-nowrap shrink-0">
                                {{ \Carbon\Carbon::parse($log->entry_at)->format('H:i') }}
                            </span>
                        </div>
                        @empty
                        <div class="py-12 text-center">
                            <div class="w-12 h-12 bg-premium-gray rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-users-slash text-gray-600 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada yang absen hari ini.</p>
                            <p class="text-gray-600 text-xs mt-1">Jadilah yang pertama!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-premium-dark border-t border-white/5 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-brand rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-flask text-white text-xs"></i>
                </div>
                <span class="font-black text-white text-sm">Lab<span class="text-gradient">Booking</span></span>
            </div>
            <p class="text-gray-600 text-xs">© {{ date('Y') }} Sistem Peminjaman Lab Bisnis Digital. All rights reserved.</p>
        </div>
    </footer>

    <!-- Floating Document Button with Embed & Download (Pojok Kanan Bawah) -->
    <div x-data="{ docOpen: false, previewOpen: false, previewUrl: '', previewTitle: '', downloadUrl: '' }" class="fixed bottom-6 right-6 z-50">
        <button @click="docOpen = !docOpen"
                class="w-14 h-14 bg-gradient-brand rounded-full shadow-2xl shadow-orange-500/40 flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 relative group"
                title="Dokumen Lab">
            <i class="fa-solid fa-file-lines text-xl" x-show="!docOpen"></i>
            <i class="fa-solid fa-xmark text-xl" x-show="docOpen" x-cloak></i>
            @if($documents->isNotEmpty())
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-brand-red rounded-full text-[10px] font-black flex items-center justify-center">
                    {{ $documents->count() }}
                </span>
            @endif
            <span class="absolute right-16 bg-black/90 text-white text-xs font-bold px-3 py-1.5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap shadow-xl">
                Dokumen Lab
            </span>
        </button>

        <!-- Document Panel -->
        <div x-show="docOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             x-cloak
             @click.outside="docOpen = false"
             class="absolute bottom-16 right-0 w-84 sm:w-96 bg-premium-dark border border-white/10 rounded-2xl shadow-2xl shadow-black/90 overflow-hidden">

            <div class="px-5 py-4 border-b border-white/5 bg-premium-gray/40">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-brand rounded-xl flex items-center justify-center shadow">
                        <i class="fa-solid fa-folder-open text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm">Dokumen Lab Bisnis Digital</p>
                        <p class="text-gray-500 text-xs">Unduh atau preview formulir & panduan</p>
                    </div>
                </div>
            </div>

            <div class="max-h-72 overflow-y-auto divide-y divide-white/5">
                @forelse($documents as $doc)
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
                    <p class="text-xs text-gray-500">Belum ada dokumen tersedia</p>
                </div>
                @endforelse
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
    </div>

</body>
</html>
