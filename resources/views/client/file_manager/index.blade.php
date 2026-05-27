<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-455 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.25A2.25 2.25 0 004.5 17.5h15a2.25 2.25 0 002.25-2.25M12 9.75V3m0 0L9 6m3-3l3 3" />
            </svg>
            {{ __('Pengelola File') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:client.file-manager :subdomain="$subdomain" />
        </div>
    </div>
</x-app-layout>
