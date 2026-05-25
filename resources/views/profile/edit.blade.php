@if(Auth::user()->role === 'Admin')
    <x-admin-layout>
        <x-slot name="header">
            <h2 class="font-bold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Pengaturan Akun') }}
            </h2>
        </x-slot>

        <div class="py-6 sm:py-8 max-w-4xl mx-auto px-4 sm:px-0 space-y-6 select-none animate-fade-in">
            <div class="glass-panel p-6 sm:p-8 border-neutral-900">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-panel p-6 sm:p-8 border-neutral-900">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="glass-panel p-6 sm:p-8 border-neutral-900">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-admin-layout>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-bold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Pengaturan Akun') }}
            </h2>
        </x-slot>

        <div class="py-6 sm:py-8 max-w-4xl mx-auto px-4 sm:px-0 space-y-6 select-none animate-fade-in">
            <div class="glass-panel p-6 sm:p-8 border-neutral-900">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-panel p-6 sm:p-8 border-neutral-900">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="glass-panel p-6 sm:p-8 border-neutral-900">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-app-layout>
@endif
