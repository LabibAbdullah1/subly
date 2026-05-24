<section>
    <header class="mb-6">
        <h2 class="text-base font-bold text-white tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1.5 text-xs text-neutral-500 font-medium">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1.5 block w-full" autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs font-semibold" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1.5 block w-full" autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs font-semibold" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1.5 block w-full" autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs font-semibold" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="sm:w-auto px-6">{{ __('Simpan Kata Sandi') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-green-400 flex items-center gap-1.5 bg-green-500/10 px-3 py-1.5 rounded-xl border border-green-500/20 font-semibold"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
