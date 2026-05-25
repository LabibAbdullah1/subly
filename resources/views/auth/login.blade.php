<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="email" class="block mt-1.5 w-full font-semibold" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
                @if (Route::has('password.request'))
                    <a class="text-[10px] text-neutral-450 hover:text-white font-extrabold uppercase tracking-wider transition-colors" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Lupa kata sandi?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1.5 w-full font-semibold"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me Checkbox -->
        <div class="block pt-1 select-none">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-neutral-850 bg-black text-white focus:ring-offset-0 focus:ring-0 focus:ring-transparent h-4 w-4 cursor-pointer checked:bg-neutral-200 checked:border-neutral-200" name="remember">
                <span class="ms-2.5 text-xs text-neutral-450 group-hover:text-neutral-300 font-semibold transition-colors">{{ __('Tetap masuk') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="h-12 uppercase font-extrabold text-xs tracking-wider">
                {{ __('Masuk Akun') }}
            </x-primary-button>
        </div>
        
        @if (Route::has('register'))
            <p class="text-center text-[10px] text-neutral-500 mt-6 select-none font-bold uppercase tracking-wider">
                Belum punya akun? 
                <a href="{{ route('register') }}" wire:navigate class="text-white hover:text-neutral-350 transition-colors">Daftar sekarang</a>
            </p>
        @endif
    </form>
</x-guest-layout>
