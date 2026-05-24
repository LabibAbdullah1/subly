<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Ringkasan Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto select-none px-4 sm:px-0">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="flex flex-col gap-4 text-center items-center justify-center py-8">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-neutral-950 border border-neutral-900 shadow-md">
                    <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white tracking-wide">Selamat datang kembali, {{ Auth::user()->name }}!</h3>
                    <p class="text-xs text-neutral-500 font-medium mt-1">Anda telah masuk dengan aman ke panel infrastruktur Subly Anda.</p>
                </div>
                <div class="pt-4">
                    <a href="{{ route('client.dashboard') }}" class="btn-primary inline-flex items-center gap-2">
                        <span>{{ __('Buka Konsol Klien') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
