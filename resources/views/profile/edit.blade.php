<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Profile Configurations</h1>
            <p class="text-xs text-neutral-500 font-medium">Update account information, modify login passwords, or deprovision access keys.</p>
        </div>

        <div class="space-y-6">
            <!-- Profile Info Card -->
            <div class="glass-panel p-6 sm:p-8 border-neutral-900 hover:border-neutral-900 transition-all duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Form Card -->
            <div class="glass-panel p-6 sm:p-8 border-neutral-900 hover:border-neutral-900 transition-all duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Danger Deprovisioning Card -->
            <div class="glass-panel p-6 sm:p-8 border-neutral-900 hover:border-neutral-900 bg-red-950/5 transition-all duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
