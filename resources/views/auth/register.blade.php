<x-guest-layout>
    <div class="mb-7">
        <h2 class="text-2xl font-black text-white">Buat Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar untuk menggunakan Lab<span class="text-gradient font-bold">Booking</span></p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')"
                          required autofocus autocomplete="name"
                          placeholder="Nama lengkap Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- NIM / NIP -->
        <div>
            <x-input-label for="nim_nip" :value="__('NIM / NIP')" />
            <x-text-input id="nim_nip" type="text" name="nim_nip" :value="old('nim_nip')"
                          required placeholder="Masukkan NIM atau NIP Anda" />
            <x-input-error :messages="$errors->get('nim_nip')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                          required autocomplete="username"
                          placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                          required autocomplete="new-password"
                          placeholder="Min. 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                          required autocomplete="new-password"
                          placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <x-primary-button class="w-full justify-center py-3 mt-2">
            <i class="fa-solid fa-user-plus text-xs"></i>
            {{ __('Daftar') }}
        </x-primary-button>

        <!-- Login link -->
        <p class="text-center text-sm text-gray-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-brand-orange font-semibold hover:text-brand-red transition-colors ml-1">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>
