<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            {{ __('Panel Utama') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-8 select-none">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5 px-4 sm:px-0">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Ringkasan Konsol Sistem</h1>
            <p class="text-xs text-neutral-500 font-medium">Analitik platform global, log provisioning, dan permintaan dukungan langsung.</p>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 px-4 sm:px-0">
            <!-- Total Users -->
            <div class="glass-panel p-6 relative overflow-hidden group hover-lift border-neutral-900 hover:border-neutral-800">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/2 rounded-full blur-xl group-hover:bg-white/5 transition-all duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest">Total Klien</p>
                        <p class="text-2xl font-bold text-white tracking-tight mt-1.5">{{ $totalUsers }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-900 border border-neutral-850 text-neutral-400 group-hover:text-white group-hover:border-neutral-700 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Queued Deployments -->
            <div class="glass-panel p-6 relative overflow-hidden group hover-lift border-neutral-900 hover:border-neutral-800">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/2 rounded-full blur-xl group-hover:bg-white/5 transition-all duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest">Deployment</p>
                        <p class="text-2xl font-bold text-white tracking-tight mt-1.5 flex items-baseline gap-1.5">
                            {{ $queuedDeployments }}
                            <span class="text-[10px] font-bold text-neutral-500 uppercase tracking-wide">Antrian</span>
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-900 border border-neutral-850 text-neutral-400 group-hover:text-white group-hover:border-neutral-700 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Unread Chats -->
            <div class="glass-panel p-6 relative overflow-hidden group hover-lift border-neutral-900 hover:border-neutral-800">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/2 rounded-full blur-xl group-hover:bg-white/5 transition-all duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest">Chat Belum Dibaca</p>
                        <p class="text-2xl font-bold text-white tracking-tight mt-1.5 flex items-baseline gap-1.5">
                            {{ $unreadChats }}
                            @if($unreadChats > 0)
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-900 border border-neutral-850 text-neutral-400 group-hover:text-white group-hover:border-neutral-700 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="glass-panel p-6 relative overflow-hidden group hover-lift border-neutral-900 hover:border-neutral-800">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/2 rounded-full blur-xl group-hover:bg-white/5 transition-all duration-500"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest">Pendapatan Bulanan</p>
                        <p class="text-xl sm:text-2xl font-bold text-white tracking-tight mt-1.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-neutral-900 border border-neutral-850 text-neutral-400 group-hover:text-white group-hover:border-neutral-700 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Logs Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 sm:px-0">
            <!-- Recent Deployments -->
            <div class="glass-panel overflow-hidden border-neutral-900">
                <div class="p-5 border-b border-neutral-900/60 bg-neutral-950/40 flex justify-between items-center">
                    <div class="flex flex-col gap-0.5">
                        <h3 class="text-sm font-semibold text-white">Deployment Terbaru</h3>
                        <p class="text-[10px] text-neutral-500 font-medium">Antrean build terbaru di latar belakang.</p>
                    </div>
                    <a href="{{ route('admin.deployments.index') }}" class="text-xs font-semibold text-neutral-400 hover:text-white transition-colors active:scale-[0.98]">
                                                Lihat Antrian →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-th text-[10px]">Klien</th>
                                <th class="table-th text-[10px]">Subdomain</th>
                                <th class="table-th text-[10px] text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/40">
                            @forelse($recentDeployments as $deployment)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td text-xs font-semibold text-neutral-300">{{ $deployment->subdomain?->user?->name ?? 'Tidak Diketahui' }}</td>
                                <td class="table-td text-xs font-mono text-neutral-450">
                                    <div class="flex items-center gap-1.5">
                                        <span>{{ $deployment->subdomain?->full_domain ?? 'N/A' }}</span>
                                        @if(str_contains($deployment->notes ?? '', 'GitHub'))
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center gap-0.5" title="GitHub Deployment">
                                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                git
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="table-td text-right">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase rounded-md tracking-wider border
                                        {{ $deployment->status === 'success' ? 'bg-neutral-900/50 text-neutral-300 border-neutral-800' : '' }}
                                        {{ $deployment->status === 'queued' ? 'bg-neutral-900/50 text-neutral-500 border-neutral-900' : '' }}
                                        {{ $deployment->status === 'processing' ? 'bg-neutral-900/50 text-white border-neutral-750' : '' }}
                                        {{ $deployment->status === 'error' ? 'bg-red-950/20 text-red-400 border-red-900/20' : '' }}
                                    ">
                                        {{ $deployment->status === 'success' ? 'Sukses' : ($deployment->status === 'queued' ? 'Antrean' : ($deployment->status === 'processing' ? 'Diproses' : ($deployment->status === 'error' ? 'Gagal' : $deployment->status))) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="table-td text-center py-8 text-xs text-neutral-500 font-medium">Tidak ada deployment terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Chats -->
            <div class="glass-panel overflow-hidden border-neutral-900">
                <div class="p-5 border-b border-neutral-900/60 bg-neutral-950/40 flex justify-between items-center">
                    <div class="flex flex-col gap-0.5">
                        <h3 class="text-sm font-semibold text-white">Chat Dukungan Terbaru</h3>
                        <p class="text-[10px] text-neutral-500 font-medium">Saluran chat klien aktif terbaru.</p>
                    </div>
                    <a href="{{ route('admin.chat.index') }}" class="text-xs font-semibold text-neutral-400 hover:text-white transition-colors active:scale-[0.98]">
                                                Live Chat →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-th text-[10px]">Klien</th>
                                <th class="table-th text-[10px]">Pesan</th>
                                <th class="table-th text-[10px] text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/40">
                            @forelse($recentChats as $chat)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td text-xs font-semibold text-neutral-300 transition-colors group-hover:text-white">{{ $chat->user->name ?? 'Pengguna Dihapus' }}</td>
                                <td class="table-td text-xs text-neutral-400 truncate max-w-[150px]">{{ $chat->message }}</td>
                                <td class="table-td text-right">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase rounded-md tracking-wider border
                                        {{ $chat->is_read ? 'bg-neutral-900/50 text-neutral-450 border-neutral-900' : 'bg-white text-black border-transparent' }}
                                    ">
                                        {{ $chat->is_read ? 'Dibaca' : 'Baru' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="table-td text-center py-8 text-xs text-neutral-500 font-medium">Tidak ada pesan terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
