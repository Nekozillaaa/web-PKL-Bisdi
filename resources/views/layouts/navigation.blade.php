<nav x-data="{ open: false }" 
     class="sticky top-0 z-50 glass-dark border-b border-white/5 transition-all duration-300">

    @php
        $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
        $recentNotifications = auth()->check() ? auth()->user()->notifications()->take(5)->get() : collect();
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 bg-gradient-brand rounded-xl flex items-center justify-center shadow-lg shadow-brand-orange/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-flask text-white text-sm"></i>
                    </div>
                    <div>
                        <span class="font-black text-white text-base leading-none tracking-tight">Lab</span><span class="font-black text-gradient text-base leading-none tracking-tight">Booking</span>
                        <p class="text-[9px] font-semibold text-gray-500 uppercase tracking-widest mt-0.5">Sistem Peminjaman Lab</p>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden sm:flex items-center gap-6">
                <a href="{{ route('dashboard') }}" 
                   class="text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-brand-orange' : 'text-gray-400 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high mr-1.5 text-xs"></i>Dashboard
                </a>

                @if(auth()->user()->role == 'mahasiswa')
                    <a href="{{ route('bookings.create') }}"
                       class="text-sm font-medium transition-colors duration-200 {{ request()->routeIs('bookings.create') ? 'text-brand-orange' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-plus-circle mr-1.5 text-xs"></i>Ajukan Peminjaman
                    </a>
                @endif

                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('admin.items.index') }}"
                       class="text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.items.*') ? 'text-brand-orange' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-boxes-stacked mr-1.5 text-xs"></i>Kelola Barang
                    </a>
                @endif
            </div>

            <!-- Right: Notifications & User Profile -->
            <div class="hidden sm:flex sm:items-center gap-4">

                <!-- Notifications Bell Dropdown -->
                <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                    <button @click="notifOpen = !notifOpen" 
                            type="button"
                            class="relative p-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition-colors focus:outline-none"
                            title="Notifikasi">
                        <i class="fa-regular fa-bell text-lg"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-4 min-w-[16px] px-1 items-center justify-center rounded-full bg-brand-orange text-[10px] font-black text-white shadow-lg shadow-orange-500/50 animate-pulse">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="notifOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         x-cloak
                         class="absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-premium-dark border border-white/10 shadow-2xl shadow-black/80 py-2 z-50 overflow-hidden">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-bell text-brand-orange text-xs"></i>
                                <span class="font-bold text-white text-xs uppercase tracking-wider">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-brand-orange/20 text-brand-orange text-[10px] font-bold">
                                        {{ $unreadCount }} Baru
                                    </span>
                                @endif
                            </div>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-gray-400 hover:text-brand-orange transition-colors">
                                        Tandai semua dibaca
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-72 overflow-y-auto divide-y divide-white/5">
                            @forelse($recentNotifications as $notif)
                                <a href="{{ route('notifications.read', $notif->id) }}" 
                                   class="block p-3.5 hover:bg-white/5 transition-colors {{ is_null($notif->read_at) ? 'bg-brand-orange/5' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ is_null($notif->read_at) ? 'bg-brand-orange/20 text-brand-orange' : 'bg-white/5 text-gray-400' }}">
                                            <i class="fa-solid {{ $notif->data['icon'] ?? 'fa-bell text-brand-orange' }} text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-white truncate">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                                            <p class="text-xs text-gray-400 line-clamp-2 mt-0.5 leading-snug">{{ $notif->data['message'] ?? '' }}</p>
                                            <span class="text-[10px] text-gray-500 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="py-8 text-center">
                                    <i class="fa-regular fa-bell-slash text-gray-600 text-xl mb-2"></i>
                                    <p class="text-xs text-gray-500">Tidak ada notifikasi baru</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-2.5 border-t border-white/5 bg-white/2 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-brand-orange hover:underline">
                                Lihat Semua Notifikasi &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 group cursor-pointer">
                    <div class="w-8 h-8 bg-gradient-brand rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md shadow-brand-orange/20 group-hover:scale-110 transition-transform">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block">
                        <p class="text-sm font-semibold text-white leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-500 mt-0.5 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-brand-orange transition-colors px-3 py-2 rounded-lg hover:bg-white/5">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="hidden md:inline">Keluar</span>
                    </button>
                </form>
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden gap-2">
                <!-- Mobile Notification Icon -->
                <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-400 hover:text-white">
                    <i class="fa-regular fa-bell text-lg"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 flex h-3.5 min-w-[14px] px-1 items-center justify-center rounded-full bg-brand-orange text-[9px] font-black text-white">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>

                <button @click="open = !open" 
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150">
                    <i class="fa-solid text-lg" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden glass-dark border-t border-white/5">
        <div class="px-4 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-orange/10 text-brand-orange' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-gauge-high mr-2 text-xs"></i>Dashboard
            </a>

            @if(auth()->user()->role == 'mahasiswa')
                <a href="{{ route('bookings.create') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('bookings.create') ? 'bg-brand-orange/10 text-brand-orange' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-plus-circle mr-2 text-xs"></i>Ajukan Peminjaman
                </a>
            @endif

            @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.items.index') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('admin.items.*') ? 'bg-brand-orange/10 text-brand-orange' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <i class="fa-solid fa-boxes-stacked mr-2 text-xs"></i>Kelola Barang
                </a>
            @endif

            <a href="{{ route('notifications.index') }}"
               class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('notifications.*') ? 'bg-brand-orange/10 text-brand-orange' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-bell text-xs"></i>
                    <span>Notifikasi</span>
                </div>
                @if($unreadCount > 0)
                    <span class="px-2 py-0.5 bg-brand-orange text-white text-[10px] font-bold rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        <!-- Mobile User Info -->
        <div class="px-4 pt-4 pb-5 border-t border-white/5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-brand rounded-full flex items-center justify-center text-white font-bold shadow-md">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-white text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }} — {{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl text-sm text-gray-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fa-solid fa-user-gear mr-2 text-xs"></i>Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 rounded-xl text-sm text-gray-400 hover:text-brand-orange hover:bg-white/5 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2 text-xs"></i>Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
