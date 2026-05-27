<x-admin-layout>
    <style>
        /* Force extremely compact select status badges on mobile to override global browser rules */
        @media (max-width: 640px) {
            select[name="status"] {
                font-size: 10px !important;
                height: 24px !important;
                padding-top: 0px !important;
                padding-bottom: 0px !important;
                line-height: 24px !important;
                width: 90px !important;
                text-align: center !important;
                text-align-last: center !important;
            }
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Antrian Deployment') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Antrian Infrastruktur</h1>
            <p class="text-xs text-neutral-500 font-medium">Provisioning subdomain, ekstrak template paket, dan perbarui versi build.</p>
        </div>

        @if (session('success'))
            <div class="bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-950/20 border border-red-900/30 text-red-400 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ $errors->first() }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Active Queue Column -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Antrian Aktif
                    </h3>
                    <span class="text-[10px] font-bold text-neutral-450 bg-neutral-900 border border-neutral-850 px-2 py-0.5 rounded-md tracking-wider uppercase">{{ $pendingDeployments->count() }} Tertunda</span>
                </div>
                
                <div class="glass-panel border-neutral-900 relative z-20">
                    <div class="overflow-x-auto md:overflow-visible">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th text-[10px]">Target & Artefak</th>
                                    <th class="table-th text-right text-[10px] pr-6">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/40">
                                @forelse($pendingDeployments as $deployment)
                                    <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                        <td class="table-td">
                                            <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $deployment->subdomain?->full_domain ?? 'Subdomain Dihapus' }}</div>
                                            <div class="text-[10px] text-neutral-550 font-semibold mt-0.5">Pemilik: {{ $deployment->subdomain?->user?->name ?? 'Pengguna Dihapus/Tidak Dikenal' }}</div>
                                            
                                            <div class="flex items-center gap-2 mt-2 select-none">
                                                @if(str_contains($deployment->notes ?? '', 'GitHub'))
                                                    <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-neutral-900 border border-neutral-850 text-emerald-450 flex items-center gap-1.5" title="{{ $deployment->notes }}">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                        v{{ $deployment->version }} GitHub
                                                    </span>
                                                @else
                                                    <a href="{{ route('admin.deployments.download', $deployment) }}" class="text-[10px] font-bold px-2 py-1 rounded-md bg-neutral-900 border border-neutral-850 text-neutral-300 hover:text-white hover:border-neutral-700 transition-all flex items-center gap-1.5 active:scale-[0.98]">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        v{{ $deployment->version }} ZIP
                                                    </a>
                                                @endif
                                                <span class="text-[10px] text-neutral-500 font-semibold italic">{{ $deployment->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if(str_contains($deployment->notes ?? '', 'GitHub'))
                                                <div class="text-[9px] text-neutral-450 mt-1.5 select-all font-semibold truncate max-w-[220px]" title="{{ $deployment->notes }}">
                                                    {{ str_replace('GitHub Pull - ', '', $deployment->notes) }}
                                                </div>
                                            @elseif($deployment->notes)
                                                <div class="text-[9px] text-neutral-550 mt-1.5 italic font-semibold truncate max-w-[220px]" title="{{ $deployment->notes }}">
                                                    Catatan: {{ $deployment->notes }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="table-td text-right pr-6">
                                            <div class="flex flex-wrap items-center justify-end gap-1.5 sm:gap-2">
                                                <form action="{{ route('admin.deployments.setup_db', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] rounded-lg bg-neutral-900 border border-neutral-850 hover:border-neutral-700 text-neutral-300 hover:text-white px-2.5 py-1.5 transition-all font-bold active:scale-[0.96] cursor-pointer" title="Setup Virtual Host & Database">
                                                        Provisioning Server
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.deployments.extract', $deployment) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui deployment ini? File ZIP sudah ter-upload di cPanel. Dengan menyetujuinya, status akan diubah menjadi Sukses sehingga Anda tinggal mengekstrak file ZIP tersebut secara manual di cPanel.')">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] sm:text-[11px] rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-1 hover:bg-emerald-500/20 transition-all font-medium flex items-center gap-1" title="Setujui deployment dan simpan file ZIP di server cPanel untuk diekstrak manual">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        Setujui & Deploy
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf @method('PUT')
                                                    <select name="status" class="text-[10px] rounded-lg bg-neutral-950 border border-neutral-900 text-neutral-300 hover:bg-neutral-900 focus:ring-neutral-700 px-2 pr-6 shadow-inner cursor-pointer font-bold w-28 text-center" style="font-size: 10px !important; height: 26px !important; padding-top: 0px !important; padding-bottom: 0px !important;" onchange="this.form.submit()">
                                                        <option value="queued" {{ $deployment->status == 'queued' ? 'selected' : '' }}>Antri</option>
                                                        <option value="processing" {{ $deployment->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                                        <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Sukses</option>
                                                        <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Gagal</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">
                                            Tidak ada deployment aktif.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Deployment History Column -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-neutral-450 uppercase tracking-wider flex items-center gap-2 px-2">
                    <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Deployment
                </h3>

                <div class="glass-panel border-neutral-900 relative z-10">
                    <div class="overflow-x-auto md:overflow-visible">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th text-[10px]">Artefak</th>
                                    <th class="table-th text-[10px]">Status</th>
                                    <th class="table-th text-right text-[10px] pr-6">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/40">
                                @forelse($completedDeployments as $deployment)
                                    <tr class="hover:bg-neutral-900/20 transition-all duration-350 group/row">
                                        <td class="table-td px-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-xs font-bold text-neutral-200 group-hover/row:text-white transition-colors">{{ $deployment->subdomain?->full_domain ?? 'Subdomain Dihapus' }}</div>
                                                    <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">Pemilik: {{ $deployment->subdomain?->user?->name ?? 'Pengguna Dihapus/Tidak Dikenal' }}</div>
                                                    <div class="flex flex-wrap items-center gap-2 mt-1 select-none">
                                                        <span class="text-[10px] text-neutral-500 font-bold font-mono">Build v{{ $deployment->version }}</span>
                                                        @if(str_contains($deployment->notes ?? '', 'GitHub'))
                                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-400 flex items-center gap-0.5" title="{{ $deployment->notes }}">
                                                                <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                                github
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if(str_contains($deployment->notes ?? '', 'GitHub'))
                                                        <div class="text-[9px] text-neutral-450 mt-1 select-all font-semibold truncate max-w-[220px]" title="{{ $deployment->notes }}">
                                                            {{ str_replace('GitHub Pull - ', '', $deployment->notes) }}
                                                        </div>
                                                    @elseif($deployment->notes)
                                                        <div class="text-[9px] text-neutral-500 mt-1 italic font-semibold truncate max-w-[220px]" title="{{ $deployment->notes }}">
                                                            Catatan: {{ $deployment->notes }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.deployments.download', $deployment) }}" class="opacity-0 group-hover/row:opacity-100 transition-opacity p-2 border border-neutral-850 hover:border-neutral-700 bg-neutral-900 hover:bg-neutral-950 text-neutral-400 hover:text-white rounded-lg active:scale-[0.94] shrink-0 ml-4" title="Download ZIP">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf @method('PUT')
                                                    <select name="status" class="text-[10px] uppercase tracking-wider font-bold rounded-lg border-none px-2 cursor-pointer w-28 text-center {{ $deployment->status == 'success' ? 'bg-neutral-900 text-neutral-350' : 'bg-red-950/30 text-red-400 border border-red-900/10' }}" style="font-size: 10px !important; height: 26px !important; padding-top: 0px !important; padding-bottom: 0px !important;" onchange="this.form.submit()">
                                                        <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Sukses</option>
                                                        <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Gagal</option>
                                                        <option value="queued">Kembalikan ke Antrian</option>
                                                    </select>
                                                </form>
                                                <form action="{{ route('admin.deployments.destroy', $deployment) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus deployment ini? Tindakan ini akan menghapus file ZIP dan tidak dapat dibatalkan.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-neutral-600 hover:text-red-400 opacity-0 group-hover/row:opacity-100 transition-all p-1.5 rounded hover:bg-neutral-900/50 cursor-pointer" title="Hapus Deployment">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="table-td text-right text-[10px] text-neutral-500 font-semibold pr-6">
                                            {{ $deployment->updated_at->format('d M, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">Riwayat kosong.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($completedDeployments->hasPages())
                        <div class="p-3 border-t border-neutral-900 bg-neutral-950/40">
                            {{ $completedDeployments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
