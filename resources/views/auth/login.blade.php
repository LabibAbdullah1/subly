<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@subly.my.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-primary-400 hover:text-primary-300 font-medium transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-700 bg-gray-900 text-primary-500 shadow-sm focus:ring-primary-500/50" name="remember">
                <span class="ms-2 text-sm text-gray-400 group-hover:text-gray-300 transition-colors">{{ __('Stay signed in') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button>
                {{ __('Access Account') }}
            </x-primary-button>
        </div>
        
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-white hover:text-primary-400 font-medium transition-colors">Sign up</a>
            </p>
        @endif
    </form>
</x-guest-layout>
