<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="email" class="block mt-1.5 w-full font-semibold" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="john@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="password" class="block mt-1.5 w-full font-semibold" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
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
                {{ __('Atur Ulang Kata Sandi') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
