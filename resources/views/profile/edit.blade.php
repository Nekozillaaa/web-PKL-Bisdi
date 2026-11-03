<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-heading">Akun Saya</p>
            <h2 class="text-2xl font-black text-white leading-tight">
                Pengaturan <span class="text-gradient">Profil</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <div class="px-6 py-5 border-b border-white/5 bg-premium-gray/30">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-user text-brand-orange"></i>
                    Informasi Profil
                </h3>
            </div>
            <div class="p-6 max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card-dark rounded-2xl overflow-hidden border border-white/7">
            <div class="px-6 py-5 border-b border-white/5 bg-premium-gray/30">
                <h3 class="font-bold text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-lock text-brand-orange"></i>
                    Ubah Password
                </h3>
            </div>
            <div class="p-6 max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card-dark rounded-2xl overflow-hidden border border-red-500/10">
            <div class="px-6 py-5 border-b border-white/5 bg-red-500/5">
                <h3 class="font-bold text-red-400 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Zona Berbahaya
                </h3>
            </div>
            <div class="p-6 max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>
