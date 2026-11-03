<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-5 flex items-center gap-2 text-sm text-green-400 bg-green-500/10 border border-green-500/20 rounded-xl px-4 py-3">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-7">
        <h2 class="text-2xl font-black text-white">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-1">Masuk ke akun Lab<span class="text-gradient font-bold">Booking</span> Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          required autofocus autocomplete="username"
                          placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                          required autocomplete="current-password"
                          placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox"
                       class="w-4 h-4 rounded border-white/20 bg-premium-gray text-brand-orange focus:ring-brand-orange/30"
                       name="remember">
                <span class="text-sm text-gray-400">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-gray-500 hover:text-brand-orange transition-colors">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <x-primary-button class="w-full justify-center py-3">
            <i class="fa-solid fa-right-to-bracket text-xs"></i>
            {{ __('Masuk') }}
        </x-primary-button>

        <!-- Register link -->
        <p class="text-center text-sm text-gray-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-brand-orange font-semibold hover:text-brand-red transition-colors ml-1">Daftar sekarang</a>
        </p>
    </form>
</x-guest-layout>
