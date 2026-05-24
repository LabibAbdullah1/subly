<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Deployment
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 select-none">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-neutral-900 bg-neutral-950/20">
                    <h3 class="text-sm font-bold text-white tracking-tight uppercase tracking-wider">Seluruh Riwayat Deployment</h3>
                    <p class="text-xs text-neutral-450 mt-1 font-medium">Daftar rekaman seluruh aktivitas deployment pada semua subdomain Anda.</p>
                </div>

                <div class="table-container">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-neutral-950/80">
                                <th class="table-th">Subdomain</th>
                                <th class="table-th">Tanggal & Waktu</th>
                                <th class="table-th text-center">Status</th>
                                <th class="table-th">Catatan</th>
                                <th class="table-th text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/50">
                            @forelse($deployments as $deployment)
                                <tr class="group hover:bg-neutral-900/10 transition-colors">
                                    <td class="table-td">
                                        <div class="flex flex-col">
                                            <a href="https://{{ $deployment->subdomain->full_domain }}" target="_blank" class="font-bold text-white hover:text-neutral-350 flex items-center gap-1.5 transition-colors">
                                                {{ $deployment->subdomain->full_domain }}
                                                <svg class="w-3.5 h-3.5 text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                            </a>
                                            <span class="text-[9px] text-neutral-500 font-mono mt-0.5">ID: #{{ $deployment->id }}</span>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <div class="text-xs font-semibold text-neutral-200">{{ $deployment->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-neutral-550 mt-0.5 font-medium">{{ $deployment->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="table-td text-center">
                                        <span class="px-2.5 py-0.5 inline-flex text-[9px] font-bold uppercase tracking-wider rounded-md border 
                                            {{ $deployment->status === 'success' ? 'bg-neutral-900 border-neutral-850 text-white' : '' }}
                                            {{ $deployment->status === 'queued' ? 'bg-neutral-900/40 border-neutral-900 text-neutral-450' : '' }}
                                            {{ $deployment->status === 'processing' ? 'bg-neutral-900/60 border-neutral-850 text-neutral-300' : '' }}
                                            {{ $deployment->status === 'error' ? 'bg-red-950/20 border-red-900/30 text-red-400' : '' }}">
                                            {{ $deployment->status === 'success' ? 'Sukses' : ($deployment->status === 'queued' ? 'Antrean' : ($deployment->status === 'processing' ? 'Diproses' : ($deployment->status === 'error' ? 'Gagal' : $deployment->status))) }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <p class="text-xs text-neutral-350 max-w-xs truncate font-medium" title="{{ $deployment->notes }}">
                                            {{ $deployment->notes ?: '-' }}
                                        </p>
                                    </td>
                                    <td class="table-td text-right">
                                        <a href="{{ route('client.portal', $deployment->subdomain) }}" class="btn-secondary h-9 px-3.5 flex items-center justify-center text-xs font-bold uppercase tracking-wider active:scale-[0.98] inline-flex">
                                            Buka Portal
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-neutral-550 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376C1.83 15.002 2.067 13.785 3 13.125L9.932 8.5c1.24-.827 2.896-.827 4.136 0L21 13.125c.933.66 1.17 1.877.432 2.626L14.5 20.25c-1.24.827-2.896.827-4.136 0L3 15.75c-.933-.66-1.17-1.877-.432-2.626z" />
                                            </svg>
                                            <p class="text-neutral-500 text-xs font-bold uppercase tracking-widest">Belum ada riwayat deployment</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($deployments->hasPages())
                    <div class="p-6 border-t border-neutral-900 bg-neutral-950/20">
                        {{ $deployments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
