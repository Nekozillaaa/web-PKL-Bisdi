<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lab Booking') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-premium-black">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
            <!-- Background gradient decorations -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-orange rounded-full blur-[120px] opacity-10"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-brand-red rounded-full blur-[120px] opacity-10"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-premium-gray rounded-full blur-[100px] opacity-20"></div>
            </div>

            <!-- Logo / Brand -->
            <div class="relative z-10 mb-8">
                <a href="/" class="flex flex-col items-center gap-3 group">
                    <div class="w-14 h-14 bg-gradient-brand rounded-2xl flex items-center justify-center shadow-xl shadow-brand-orange/30 group-hover:scale-110 transition-transform duration-300">
                        <i class="fa-solid fa-flask text-white text-xl"></i>
                    </div>
                    <div class="text-center">
                        <p class="font-black text-xl text-white tracking-tight">
                            Lab<span class="text-gradient">Booking</span>
                        </p>
                        <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest">Sistem Peminjaman Laboratorium</p>
                    </div>
                </a>
            </div>

            <!-- Auth Card -->
            <div class="relative z-10 w-full sm:max-w-md mx-4">
                <div class="card-dark px-8 py-8 rounded-2xl shadow-2xl shadow-black/50 border border-white/5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
