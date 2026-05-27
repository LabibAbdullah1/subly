<x-admin-layout>
    <x-slot name="header">
        {{ __('Penggunaan Disk') }}
    </x-slot>

    <div class="space-y-8 animate-fade-in">
        <!-- Page Header Description -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 select-none">
            <div>
                <h2 class="text-xl font-extrabold text-white tracking-tight font-heading">MONITORING PENYIMPANAN AKTUAL</h2>
                <p class="text-xs text-neutral-450 mt-1 font-semibold leading-relaxed">
                    Menampilkan kapasitas penyimpanan fisik asli di server cPanel (Filesystem Direktori) dan ukuran database MySQL secara terpadu dan real-time.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-400">
                    Real-Time cPanel API
                </span>
            </div>
        </div>

        <!-- Metric Grid Cards -->
        @php
            $totalFileBytes = 0;
            $totalDbBytes = 0;
            $nearLimitCount = 0;
            foreach ($diskData as $item) {
                $totalFileBytes += $item['dir_bytes'];
                $totalDbBytes += $item['db_bytes'];
                if ($item['percent'] >= 80) {
                    $nearLimitCount++;
                }
            }
            $grandTotalBytes = $totalFileBytes + $totalDbBytes;
            
            $grandTotalDisplay = $grandTotalBytes >= 1073741824 
                ? round($grandTotalBytes / 1073741824, 2) . ' GB' 
                : round($grandTotalBytes / 1048576, 2) . ' MB';

            $totalFileDisplay = $totalFileBytes >= 1073741824 
                ? round($totalFileBytes / 1073741824, 2) . ' GB' 
                : round($totalFileBytes / 1048576, 2) . ' MB';

            $totalDbDisplay = $totalDbBytes >= 1073741824 
                ? round($totalDbBytes / 1073741824, 2) . ' GB' 
                : round($totalDbBytes / 1048576, 2) . ' MB';
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 select-none">
            <!-- Card 1: Grand Total -->
            <div class="glass-panel glass-panel-glow p-5 flex items-center justify-between shadow-2xl">
                <div class="flex items-center gap-4.5">
                    <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center text-white">
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-neutral-500">Total Akumulasi</span>
                        <span class="text-xl font-black text-white tracking-tight mt-0.5">{{ $grandTotalDisplay }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: File Size -->
            <div class="glass-panel glass-panel-glow p-5 flex items-center justify-between shadow-2xl">
                <div class="flex items-center gap-4.5">
                    <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center text-white">
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.25A2.25 2.25 0 004.5 17.5h15a2.25 2.25 0 002.25-2.25M12 9.75V3m0 0L9 6m3-3l3 3" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-neutral-500">Kapasitas Berkas</span>
                        <span class="text-xl font-black text-white tracking-tight mt-0.5">{{ $totalFileDisplay }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Database Size -->
            <div class="glass-panel glass-panel-glow p-5 flex items-center justify-between shadow-2xl">
                <div class="flex items-center gap-4.5">
                    <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center text-white">
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-neutral-500">Kapasitas Database</span>
                        <span class="text-xl font-black text-white tracking-tight mt-0.5">{{ $totalDbDisplay }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Near Limit -->
            <div class="glass-panel p-5 flex items-center justify-between shadow-2xl border-l-2 {{ $nearLimitCount > 0 ? 'border-l-red-500' : 'border-l-neutral-900' }}">
                <div class="flex items-center gap-4.5">
                    <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center text-white">
                        <svg class="w-5 h-5 {{ $nearLimitCount > 0 ? 'text-red-400' : 'text-neutral-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-neutral-500">Hampir Kuota Penuh</span>
                        <span class="text-xl font-black {{ $nearLimitCount > 0 ? 'text-red-400' : 'text-white' }} tracking-tight mt-0.5">
                            {{ $nearLimitCount }} Subdomain
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Datatable Card -->
        <div class="glass-panel p-0 overflow-hidden flex flex-col w-full shadow-2xl">
            <div class="px-6 py-5 border-b border-neutral-900/60 bg-neutral-950/20 flex justify-between items-center select-none">
                <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" /></svg>
                    Daftar Subdomain & Kapasitas Penyimpanan
                </h3>
            </div>

            <div class="table-container">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-neutral-950/80">
                            <th class="table-th">Subdomain / Klien</th>
                            <th class="table-th">Paket</th>
                            <th class="table-th text-center">Ukuran Files</th>
                            <th class="table-th text-center">Ukuran Database</th>
                            <th class="table-th">Rasio Penggunaan Disk (Total)</th>
                            <th class="table-th text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/50">
                        @forelse($diskData as $item)
                            @php
                                $subdomain = $item['subdomain'];
                                $progressText = $item['percent'] >= 80 ? 'text-red-400 font-bold' : 'text-neutral-350';
                                $progressBarColor = $item['percent'] >= 80 ? 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.3)]' : 'bg-white';
                            @endphp
                            <tr class="group hover:bg-neutral-900/10 transition-colors">
                                <td class="table-td">
                                    <div class="font-bold text-white group-hover:text-neutral-350 transition-colors flex items-center gap-1.5">
                                        {{ $subdomain->full_domain }}
                                        <a href="https://{{ $subdomain->full_domain }}" target="_blank" class="text-neutral-500 hover:text-white transition-colors" title="Buka Situs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                        </a>
                                        @if($subdomain->git_url)
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center gap-0.5 animate-fade-in" title="GitHub Connected ({{ $subdomain->git_branch }})">
                                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                git
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-neutral-500 mt-1 font-semibold">
                                        Klien: {{ $item['user']->name }} ({{ $item['user']->email }})
                                    </div>
                                </td>
                                <td class="table-td">
                                    <span class="text-xs font-bold text-neutral-200 block uppercase">{{ $item['plan_name'] }}</span>
                                    <span class="text-[9px] text-neutral-500 font-extrabold uppercase mt-0.5 block tracking-wider">Batas: {{ $item['max_storage_mb'] }} MB</span>
                                </td>
                                <td class="table-td text-center font-mono text-xs font-semibold text-neutral-350">
                                    {{ $item['dir_mb'] >= 1 ? $item['dir_mb'] . ' MB' : round($item['dir_bytes']/1024, 1) . ' KB' }}
                                </td>
                                <td class="table-td text-center">
                                    <span class="font-mono text-xs font-semibold text-neutral-350 block">
                                        {{ $item['db_mb'] >= 1 ? $item['db_mb'] . ' MB' : round($item['db_bytes']/1024, 1) . ' KB' }}
                                    </span>
                                    @if($item['db_bytes'] > 0)
                                        <span class="text-[8px] font-bold uppercase tracking-widest mt-0.5 block {{ $item['db_size_live'] ? 'text-emerald-500' : 'text-neutral-600' }}">
                                            {{ $item['db_size_live'] ? '● Live' : '~ Estimasi' }}
                                        </span>
                                    @else
                                        <span class="text-[8px] font-bold uppercase tracking-widest mt-0.5 block text-neutral-700">
                                            Tidak ada DB
                                        </span>
                                    @endif
                                </td>
                                <td class="table-td">
                                    <div class="flex items-center justify-between text-[10px] mb-1.5 select-none font-semibold">
                                        <span class="{{ $progressText }}">
                                            {{ $item['total_mb'] }} MB / {{ $item['max_storage_mb'] }} MB
                                        </span>
                                        <span class="{{ $progressText }}">{{ $item['percent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-neutral-900 rounded-full h-1 overflow-hidden">
                                        <div class="h-1 rounded-full transition-all duration-700 ease-out {{ $progressBarColor }}" style="width: {{ $item['percent'] }}%"></div>
                                    </div>
                                </td>
                                <td class="table-td text-right">
                                    <div class="flex items-center justify-end gap-3.5">
                                        <a href="{{ route('admin.subdomains.show', $subdomain) }}" wire:navigate class="text-xs font-bold uppercase tracking-wider text-white hover:underline transition-colors">Detail</a>
                                        <a href="{{ route('admin.users.show', $item['user']) }}" wire:navigate class="text-xs font-bold uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">Profil Klien</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-td text-center text-neutral-500 italic py-12">
                                    Belum ada subdomain aktif untuk dimonitor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
