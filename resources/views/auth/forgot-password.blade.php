<x-guest-layout>
    <div class="mb-6 text-xs font-semibold text-neutral-400 leading-relaxed bg-black/60 border border-neutral-900 rounded-xl p-4 select-none">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="email" class="block mt-1.5 w-full font-semibold" type="email" name="email" :value="old('email')" required autofocus placeholder="john@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="h-12 uppercase font-extrabold text-xs tracking-wider">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
        
        <div class="text-center mt-6 select-none font-bold uppercase tracking-wider">
            <a href="{{ route('login') }}" class="text-[10px] text-neutral-500 hover:text-white transition-colors">Return to Sign In</a>
        </div>
    </form>
</x-guest-layout>
