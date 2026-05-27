<div>
    <!-- Dynamic Alerts -->
    @if ($successMessage || session('success'))
        <div class="bg-neutral-950/60 backdrop-blur-xl border border-neutral-900 border-l-2 border-l-emerald-500 text-white px-4 py-3.5 rounded-2xl flex items-center gap-3.5 animate-fade-in shadow-[0_24px_50px_-12px_rgba(0,0,0,0.85)] relative overflow-hidden mb-6" role="alert">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/2 to-transparent pointer-events-none"></div>
            <div class="p-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex-shrink-0 relative z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
            </div>
            <div class="flex-1 flex flex-col gap-0.5 relative z-10">
                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Sukses</p>
                <p class="text-xs text-neutral-300 font-semibold tracking-wide">{{ $successMessage ?: session('success') }}</p>
            </div>
        </div>
    @endif
    @if ($errorMessage || $errors->any())
        <div class="bg-neutral-950/60 backdrop-blur-xl border border-neutral-900 border-l-2 border-l-red-500 text-white px-4 py-3.5 rounded-2xl flex items-start gap-3.5 animate-fade-in shadow-[0_24px_50px_-12px_rgba(0,0,0,0.85)] relative overflow-hidden mb-6" role="alert">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/2 to-transparent pointer-events-none"></div>
            <div class="p-2 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex-shrink-0 mt-0.5 relative z-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
            </div>
            <div class="flex-1 flex flex-col gap-1 relative z-10">
                <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest">Gagal Eksekusi</p>
                <ul class="text-xs text-neutral-350 font-semibold tracking-wide space-y-1 list-none">
                    @if($errorMessage)
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                            <span>{{ $errorMessage }}</span>
                        </li>
                    @endif
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 shrink-0"></span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Back to Hosting Portal Shortcut & Title block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 select-none mb-6">
        <div>
            <a href="{{ route('client.portal', $subdomain->id) }}" class="text-neutral-450 hover:text-white flex items-center gap-2 transition-colors text-xs font-bold uppercase tracking-wider w-fit active:scale-98">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali ke Portal
            </a>
            <h1 class="text-lg font-black text-white tracking-tight mt-3 uppercase">File Manager: {{ $subdomain->name }}</h1>
            <p class="text-[10px] text-neutral-500 font-bold tracking-wide mt-0.5">Kelola berkas lokal di document root server Anda secara mandiri.</p>
        </div>
    </div>

    <!-- Breadcrumbs Navigation Header -->
    <div class="glass-panel px-6 py-4 flex items-center justify-between shadow-lg mb-6">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-neutral-400 select-none">
            <!-- Folder open icon -->
            <svg class="w-4 h-4 text-neutral-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5M3.75 5.25h16.5M3.75 18.75h16.5" />
            </svg>
            <a href="#" wire:click.prevent="navigateTo('')" class="hover:text-white transition-colors">Root</a>
            @foreach($breadcrumbs as $bc)
                <span class="text-neutral-600 font-mono">/</span>
                <a href="#" wire:click.prevent="navigateTo('{{ $bc['path'] }}')" class="hover:text-white transition-colors max-w-[80px] sm:max-w-xs truncate">{{ $bc['name'] }}</a>
            @endforeach
        </div>
        @if($requestedPath !== '')
            <a href="#" wire:click.prevent="goUp" class="text-[9px] font-black uppercase tracking-widest text-neutral-350 hover:text-white transition-all duration-200 flex items-center gap-1 bg-neutral-900 border border-neutral-850 hover:border-neutral-700 px-3 py-1.5 rounded-lg active:scale-95">
                <svg class="w-3 h-3 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Naik Satu Tingkat
            </a>
        @endif
    </div>

    <!-- Datatable Directory and File browser Card -->
    <div class="glass-panel p-0 overflow-hidden flex flex-col w-full shadow-2xl relative">
        <!-- SPA Loading Overlay -->
        <div wire:loading class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center z-50 transition-all duration-300">
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-neutral-950 border border-neutral-900 shadow-2xl animate-scale-up">
                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-[9px] font-black uppercase tracking-wider text-neutral-300">Memuat berkas...</span>
            </div>
        </div>

        <div class="px-6 py-4.5 border-b border-neutral-900/60 bg-neutral-950/20 flex justify-between items-center select-none">
            <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" /></svg>
                Daftar Berkas & Folder
            </h3>
            <span class="text-[9px] font-bold bg-neutral-900 border border-neutral-850 px-2 py-0.5 rounded text-neutral-500 uppercase tracking-wider">
                Total: {{ count($folders) + count($files) }} Item
            </span>
        </div>

        <div class="table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-neutral-950/80">
                        <th class="table-th">Nama</th>
                        <th class="table-th text-center">Ukuran</th>
                        <th class="table-th text-center">Waktu Modifikasi</th>
                        <th class="table-th text-right pr-8">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-900/50">
                    <!-- Directories (Folders) Listing -->
                    @foreach($folders as $folder)
                        <tr class="group hover:bg-neutral-900/10 transition-colors">
                            <td class="table-td">
                                <a href="#" wire:click.prevent="navigateTo('{{ $folder['path'] }}')" class="font-bold text-white hover:text-neutral-350 flex items-center gap-3 transition-colors select-none">
                                    <!-- Folder SVG Icon (Yellow/Amber) -->
                                    <svg class="w-5 h-5 text-amber-400 shrink-0 transition-transform group-hover:scale-102 duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-19.5 0A2.25 2.25 0 004.5 15h15a2.25 2.25 0 002.25-2.25m-19.5 0v.25A2.25 2.25 0 004.5 17.5h15a2.25 2.25 0 002.25-2.25M12 9.75V3m0 0L9 6m3-3l3 3" />
                                    </svg>
                                    <span class="truncate max-w-[200px] sm:max-w-md font-mono text-xs">{{ $folder['name'] }}</span>
                                </a>
                            </td>
                            <td class="table-td text-center font-mono text-[10px] font-bold text-neutral-600">-</td>
                            <td class="table-td text-center text-[10px] font-semibold text-neutral-450">{{ $folder['last_modified'] }}</td>
                            <td class="table-td text-right pr-8">
                                <button type="button" wire:click="confirmDelete('{{ $folder['path'] }}', '{{ $folder['name'] }}', true)" class="text-neutral-650 hover:text-red-400 transition-colors cursor-pointer p-1.5 rounded-lg hover:bg-neutral-900 border border-transparent hover:border-neutral-850 active:scale-95 inline-flex" title="Hapus Direktori (Beserta isinya)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Files Listing -->
                    @foreach($files as $file)
                        @php
                            $ext = $file['extension'];
                            $iconColor = 'text-neutral-455';
                            $iconSvg = '';
                            
                            if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico'])) {
                                $iconColor = 'text-emerald-450';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />';
                            } elseif (in_array($ext, ['zip', 'rar', 'tar', 'gz', '7z'])) {
                                $iconColor = 'text-rose-400';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />';
                            } elseif (in_array($ext, ['php', 'html', 'css', 'js', 'json', 'py', 'sh', 'sql', 'ts', 'jsx', 'tsx'])) {
                                $iconColor = 'text-cyan-400';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />';
                            } else {
                                $iconColor = 'text-neutral-450';
                                $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />';
                            }
                        @endphp
                        <tr class="group hover:bg-neutral-900/10 transition-colors">
                            <td class="table-td">
                                <div class="font-medium text-neutral-350 group-hover:text-white flex items-center gap-3 transition-colors truncate select-none">
                                    <!-- File Specific SVG Icon -->
                                    <svg class="w-5 h-5 {{ $iconColor }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        {!! $iconSvg !!}
                                    </svg>
                                    <span class="truncate max-w-[200px] sm:max-w-md font-mono text-xs">{{ $file['name'] }}</span>
                                </div>
                            </td>
                            <td class="table-td text-center font-mono text-xs font-bold text-neutral-305">{{ $file['size'] }}</td>
                            <td class="table-td text-center text-[10px] font-semibold text-neutral-450">{{ $file['last_modified'] }}</td>
                            <td class="table-td text-right pr-8">
                                <button type="button" wire:click="confirmDelete('{{ $file['path'] }}', '{{ $file['name'] }}', false)" class="text-neutral-655 hover:text-red-400 transition-colors cursor-pointer p-1.5 rounded-lg hover:bg-neutral-900 border border-transparent hover:border-neutral-850 active:scale-95 inline-flex" title="Hapus Berkas">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Empty Directory State -->
                    @if(count($folders) === 0 && count($files) === 0)
                        <tr>
                            <td colspan="4" class="table-td text-center text-neutral-500 italic py-16">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <svg class="w-8 h-8 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    <p class="text-xs font-bold">Direktori ini kosong.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Glassmorphic Delete Confirmation Modal (Livewire Driven) -->
    @if(!empty($deletePath))
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 backdrop-blur-md transition-all duration-300 select-none animate-fade-in">
            <div class="bg-neutral-950 border border-neutral-900 rounded-3xl max-w-md w-full p-6 mx-4 shadow-2xl flex flex-col relative overflow-hidden transform scale-100 transition-all duration-300 animate-scale-up">
                <!-- Faint Ambient Circle -->
                <div class="absolute -right-20 -top-20 w-48 h-48 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex items-center gap-3.5 mb-5 pb-4 border-b border-neutral-900/60">
                    <div class="w-10 h-10 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-400 shrink-0 shadow-lg">
                        <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-sm font-bold text-white tracking-tight">Konfirmasi Hapus</h3>
                        <p class="text-[10px] text-neutral-500 font-bold mt-0.5">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                    </div>
                    <button type="button" wire:click="closeDeleteModal" class="absolute top-5 right-5 text-neutral-500 hover:text-white p-1.5 rounded-xl hover:bg-neutral-900 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <p class="text-xs text-neutral-400 font-semibold leading-relaxed text-left">
                    Apakah Anda yakin ingin menghapus <span class="text-white font-bold">{{ $deleteIsDir ? 'folder' : 'berkas' }}</span> di bawah ini?
                </p>

                <!-- High fidelity filename display box to prevent awkward wrapping -->
                <div class="p-3.5 bg-red-500/5 border border-red-500/10 rounded-2xl font-mono text-xs text-red-300 break-all text-center select-all my-4.5 shadow-inner">
                    {{ $deleteName }}
                </div>

                <p class="text-[10px] text-neutral-500 font-bold text-left mb-1 select-none">
                    *Item di atas akan didelete selamanya dari server cPanel Anda.
                </p>

                <div class="grid grid-cols-2 gap-3.5 select-none w-full mt-5">
                    <button type="button" wire:click="closeDeleteModal" class="h-11 rounded-xl bg-neutral-900 hover:bg-neutral-850 border border-neutral-800 text-neutral-300 hover:text-white text-xs font-bold uppercase tracking-wider flex items-center justify-center cursor-pointer transition-all duration-200 active:scale-95">
                        Batal
                    </button>
                    
                    <button type="button" wire:click="deleteItem" class="h-11 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white text-xs font-black uppercase tracking-wider flex items-center justify-center cursor-pointer transition-all duration-300 active:scale-95 shadow-[0_4px_20px_rgba(239,68,68,0.2)]">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-toast', (event) => {
                if (typeof window.showToast === 'function') {
                    window.showToast(event[0]);
                }
            });
        });
    </script>
</div>
