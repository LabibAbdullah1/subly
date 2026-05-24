<section class="space-y-6">
    <header>
        <h2 class="text-base font-bold text-red-400 tracking-wide flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1.5 text-xs text-neutral-500 font-medium">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="sm:w-auto border border-red-900/50 shadow-[0_0_15px_rgba(220,38,38,0.15)]"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-5">
            @csrf
            @method('delete')

            <div>
                <h2 class="text-base font-bold text-white tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-2 text-xs text-neutral-450 font-medium leading-relaxed">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <div>
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full sm:w-3/4"
                    placeholder="{{ __('Confirm Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs font-semibold" />
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-neutral-900">
                <x-secondary-button x-on:click="$dispatch('close')" class="px-5">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="px-5 border border-red-900/50 shadow-[0_0_15px_rgba(220,38,38,0.15)]">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
