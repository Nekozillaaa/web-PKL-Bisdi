<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Peminjaman Laboratorium — Lab Booking</title>
    <meta name="description" content="Platform peminjaman laboratorium yang mudah, cepat, dan transparan. Cek ketersediaan ruangan dan ajukan peminjaman secara realtime.">

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
                        <p class="text-[9px] text-gray-500 uppercase tracking-widest">Peminjaman Lab</p>
                    </div>
                </a>

                <!-- Desktop Links -->
                <div class="hidden md:flex items-center gap-6">
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
            <!-- Grid Pattern -->
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="max-w-3xl animate-fade-in-up">
                <span class="inline-block py-1.5 px-4 rounded-full border border-brand-orange/40 text-brand-orange text-xs font-bold tracking-widest uppercase mb-6 bg-brand-orange/5">
                    Platform Resmi Kampus
                </span>
                <h1 class="text-5xl md:text-7xl font-black text-white leading-[1.05] mb-6">
                    Sistem <span class="text-gradient">Peminjaman</span><br>Laboratorium
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
                    <div class="text-3xl font-black text-gradient">{{ $laboratories->count() }}</div>
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

    <!-- DAFTAR LABORATORIUM -->
    <section class="py-24 bg-premium-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-brand-orange font-bold tracking-widest uppercase text-xs mb-3">Fasilitas Kampus</p>
                <h2 class="text-4xl font-black text-white">Daftar Laboratorium</h2>
                <div class="w-16 h-1 bg-gradient-brand mx-auto mt-5 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($laboratories as $lab)
                <div class="group relative card-dark rounded-2xl p-7 overflow-hidden hover:border-brand-orange/40 transition-all duration-300 hover:-translate-y-1">
                    <!-- Orange glow on hover -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-brand-orange rounded-full blur-[60px] opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>

                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-brand-orange/10 border border-brand-orange/20 rounded-xl flex items-center justify-center text-brand-orange text-xl mb-5 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-microscope"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">{{ $lab->name }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $lab->description }}</p>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-gray-400 text-xs font-medium">
                                <i class="fa-solid fa-users mr-1.5 text-brand-orange"></i>{{ $lab->capacity }} Orang
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

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

    <!-- FOOTER -->
    <footer class="bg-premium-dark border-t border-white/5 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-brand rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-flask text-white text-xs"></i>
                </div>
                <span class="font-black text-white text-sm">Lab<span class="text-gradient">Booking</span></span>
            </div>
            <p class="text-gray-600 text-xs">© {{ date('Y') }} Sistem Peminjaman Laboratorium. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>