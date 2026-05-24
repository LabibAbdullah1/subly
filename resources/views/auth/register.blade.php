<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="name" class="block mt-1.5 w-full font-semibold" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="email" class="block mt-1.5 w-full font-semibold" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="john@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="password" class="block mt-1.5 w-full font-semibold"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full font-semibold"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="h-12 uppercase font-extrabold text-xs tracking-wider">
                {{ __('Buat Akun') }}
            </x-primary-button>
        </div>
        
        <p class="text-center text-[10px] text-neutral-500 mt-6 select-none font-bold uppercase tracking-wider">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-white hover:text-neutral-350 transition-colors">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>
