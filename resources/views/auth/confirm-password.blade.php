<x-guest-layout>
    <div class="mb-6 text-xs font-semibold text-neutral-400 leading-relaxed bg-black/60 border border-neutral-900 rounded-xl p-4 select-none">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold text-neutral-450 uppercase tracking-widest pl-0.5" />
            <x-text-input id="password" class="block mt-1.5 w-full font-semibold"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="h-12 uppercase font-extrabold text-xs tracking-wider">
                {{ __('Confirm Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
