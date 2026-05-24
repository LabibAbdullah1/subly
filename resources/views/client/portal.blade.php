<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-455 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
            </svg>
            {{ __('Hosting Portal') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-6">
            
            <!-- Success / Error Status Alerts -->
            @if (session('success'))
                <div class="bg-neutral-950 border border-neutral-900 text-white px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in shadow-xl" role="alert">
                    <div class="p-1 rounded-lg bg-white/5 border border-white/10 text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-neutral-950 border border-red-900/30 text-red-400 px-4 py-3.5 rounded-xl flex items-start gap-3 shadow-xl" role="alert">
                    <div class="p-1 rounded-lg bg-red-950/20 border border-red-900/30 text-red-400 flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold tracking-wide space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Back to Dashboard Shortcut -->
            <div class="mb-1">
               <a href="{{ route('client.index') }}" class="text-neutral-400 hover:text-white flex items-center gap-2 transition-colors text-xs font-bold uppercase tracking-wider w-fit active:scale-98">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to Dashboard
               </a>
            </div>

            <!-- Upload File Deployment Card -->
            <div class="glass-panel glass-panel-glow p-6 flex flex-col w-full shadow-2xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-neutral-900 border border-neutral-850 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white tracking-tight">Deploy Code</h3>
                </div>
                
                <form action="{{ route('client.deployments.store') }}" method="POST" class="flex-1 flex flex-col gap-6" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="subdomain_id" value="{{ $subdomain->id }}">
                    
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2">Project Files (.zip)</label>
                        <div class="flex justify-center h-48 border border-neutral-850 border-dashed rounded-xl transition-all relative overflow-hidden group bg-black/40 cursor-pointer hover:border-neutral-500 duration-200" id="upload-dropzone" onclick="document.getElementById('zip_file').click()">
                            <!-- Hidden File Input -->
                            <input id="zip_file" name="zip_file" type="file" class="hidden" accept=".zip" required onchange="handleFileSelect(this)">
                            
                            <!-- Default State -->
                            <div class="flex flex-col items-center justify-center space-y-2.5 w-full relative z-10 transition-opacity duration-300" id="default-upload-state">
                                <div id="upload-icon-container" class="transform group-hover:scale-102 transition-transform duration-200 text-neutral-500 group-hover:text-white">
                                    <svg class="mx-auto h-10 w-10" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" stroke-width="2">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="text-center px-4">
                                    <p class="text-xs font-bold text-neutral-350 group-hover:text-white transition-colors uppercase tracking-wider">Upload a file</p>
                                    <p class="text-[10px] text-neutral-500 font-semibold mt-1">atau seret dan lepas file ZIP ke sini</p>
                                </div>
                                <p class="text-[9px] text-neutral-550 uppercase tracking-widest font-extrabold">ZIP Maks {{ $plan ? $plan->max_storage_mb : 50 }}MB</p>
                            </div>

                            <!-- File Selected State -->
                            <div class="hidden absolute inset-0 flex-col items-center justify-center bg-white/2 backdrop-blur-sm w-full h-full p-4 z-20" id="file-selected-state">
                                <button type="button" onclick="event.stopPropagation(); cancelUpload()" class="absolute top-3 right-3 text-neutral-400 hover:text-white bg-neutral-905/80 p-2 rounded-lg border border-neutral-900 hover:border-neutral-700 transition-all shadow-lg active:scale-95 cursor-pointer" title="Cancel Upload">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-white mb-2.5 border border-white/10 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-xs font-bold text-white truncate max-w-[85%] text-center px-4" id="file-chosen-text">filename.zip</p>
                                <p class="text-[10px] text-neutral-400 font-bold mt-1" id="file-info-text">0.00 MB</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-neutral-500 mt-3 flex items-center gap-1.5 font-semibold">
                            <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                            Batas 3x upload per hari. Versi baru akan ditambahkan ke riwayat.
                        </p>
                    </div>

                    <div class="flex-1">
                        <label class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2">Catatan Deployment (Opsional)</label>
                        <textarea id="notes" name="notes" rows="2" class="input-field placeholder-neutral-600 resize-none font-medium text-xs sm:text-sm mt-1" placeholder="Contoh: Perbaikan UI, Update Fitur Login, dll."></textarea>
                    </div>

                    <!-- Chunk Upload Progress Bar -->
                    <div id="progress-container" class="hidden mb-2">
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest" id="progress-label">Uploading...</span>
                            <span class="text-xs font-bold text-white" id="progress-percent">0%</span>
                        </div>
                        <div class="w-full bg-neutral-900 rounded-full h-1 overflow-hidden shadow-inner">
                            <div id="progress-bar" class="bg-white h-1 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>

                    @php $hasActiveDeployment = $subdomain->deployments()->where('status', 'success')->exists(); @endphp
                    <button type="button" id="deploy-btn" onclick="submitDeployment(event, {{ $hasActiveDeployment ? 'true' : 'false' }})" class="w-full btn-primary h-12 flex items-center justify-center gap-2 active:scale-[0.98] cursor-pointer {{ (!$plan) ? 'opacity-40 cursor-not-allowed grayscale pointer-events-none' : '' }}" {{ (!$plan) ? 'disabled' : '' }}>
                        <span id="btn-text" class="font-extrabold uppercase text-xs tracking-wider">{{ !$plan ? 'Subscription Required' : 'Initiate Deployment' }}</span>
                        <svg id="btn-spinner" class="hidden animate-spin h-4.5 w-4.5 text-black" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Storage details & Hosting Details list -->
            <div class="glass-panel p-0 overflow-hidden flex flex-col w-full shadow-2xl">
                <div class="px-6 py-5 border-b border-neutral-900/60 bg-neutral-950/20 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        Hosted Environment
                    </h3>
                </div>

                <!-- Sleek progress details for disk usage -->
                @if($plan)
                    @php
                        $maxMB = $plan->max_storage_mb;
                        $storagePercent = $maxMB > 0 ? min(100, round(($usedStorageMB / $maxMB) * 100, 2)) : 0;
                        
                        $storageText = 'text-white border-neutral-900 bg-neutral-950';
                        if ($storagePercent > 80) {
                            $storageText = 'text-red-400 bg-red-950/20 border-red-900/30';
                        }
                    @endphp
                    <div class="p-6 border-b border-neutral-900/60">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-neutral-350">Disk Usage (ZIP Archive + Extracts)</span>
                            <span class="text-[9px] font-bold {{ $storageText }} px-2 py-0.5 rounded border tracking-wide select-none">
                                {{ $usedStorageDisplay }} / {{ $plan->max_storage_mb }} MB
                                <span class="ml-1.5 opacity-80">({{ $storagePercent }}%)</span>
                            </span>
                        </div>
                        
                        <!-- Sleek 3px loader -->
                        <div class="w-full bg-neutral-900 rounded-full h-1 overflow-hidden mb-2.5">
                            <div class="bg-white h-1 rounded-full transition-all duration-700 ease-out" style="width: {{ $storagePercent }}%"></div>
                        </div>
                        
                        @if($liveSiteBytes > 0)
                            <div class="flex justify-between items-center pt-2.5 border-t border-neutral-900/50">
                                <span class="text-[9px] uppercase tracking-widest text-neutral-500 font-bold">Uncompressed Document Root</span>
                                <span class="text-[10px] text-white font-mono font-bold">
                                    {{ $liveSiteBytes >= 1048576 ? round($liveSiteBytes / 1048576, 2) . ' MB' : round($liveSiteBytes / 1024, 2) . ' KB' }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="table-container">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-neutral-950/80">
                                <th class="table-th">Domain</th>
                                <th class="table-th text-center">Status</th>
                                <th class="table-th text-center">Expiry</th>
                                <th class="table-th text-center">Latest Build</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/50">
                            <tr class="group hover:bg-neutral-900/10 transition-colors">
                                <td class="table-td">
                                    <a href="https://{{ $subdomain->full_domain }}" target="_blank" class="font-bold text-white hover:text-neutral-350 flex items-center gap-1.5 transition-colors">
                                        {{ $subdomain->full_domain }}
                                        <svg class="w-3.5 h-3.5 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                    </a>
                                </td>
                                <td class="table-td text-center">
                                    <span class="px-2.5 py-0.5 inline-flex text-[9px] font-bold uppercase tracking-wider rounded-md border {{ $subdomain->status == 'active' ? 'bg-neutral-900 border-neutral-800 text-white' : 'bg-red-950/20 border-red-900/30 text-red-400' }}">
                                        {{ ucfirst($subdomain->status) }}
                                    </span>
                                </td>
                                <td class="table-td text-center">
                                    @if($subdomain->expired_at)
                                        <div class="text-xs font-semibold {{ $subdomain->expired_at->isPast() ? 'text-red-400' : 'text-neutral-200' }}">
                                            {{ $subdomain->expired_at->diffForHumans() }}
                                        </div>
                                        <div class="text-[10px] font-medium text-neutral-500 mt-0.5">{{ $subdomain->expired_at->format('d M Y') }}</div>
                                    @else
                                        <span class="text-neutral-500 italic text-xs font-medium">Lifetime/None</span>
                                    @endif
                                </td>
                                <td class="table-td text-center">
                                    @php $latest = $subdomain->deployments->last(); @endphp
                                    @if($latest)
                                        <div class="flex items-center justify-center gap-2 font-semibold">
                                            <span class="text-xs text-neutral-450">v{{ $latest->version }}</span>
                                            <span class="px-2 py-0.5 inline-flex text-[9px] uppercase tracking-wider rounded-full border {{ $latest->status === 'success' ? 'bg-neutral-900 border-neutral-850 text-white' : 'bg-neutral-900/40 border-neutral-900 text-neutral-550' }}">
                                                {{ ucfirst($latest->status) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-neutral-500 italic font-semibold">No deployments yet</span>
                                    @endif
                                </td>
                                <td class="table-td text-right">
                                    <div class="flex items-center justify-end gap-3.5">
                                        <a href="{{ route('client.subdomains.renew', $subdomain) }}" class="text-xs font-bold uppercase tracking-wider text-white hover:underline transition-colors">Perpanjang</a>
                                        <form action="{{ route('client.subdomains.destroy', $subdomain) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin berhenti berlangganan? Subdomain dan seluruh filenya akan dihapus permanen.');" class="deprovision-form inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold uppercase tracking-wider text-red-500 hover:text-red-400 transition-colors cursor-pointer">Berhenti</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Platform Feedback Card -->
            @php $planFeedback = $plan ? $feedbacks->get($plan->id) : null; @endphp
            @if($plan && !$planFeedback)
                <div class="glass-panel glass-panel-glow p-6 flex flex-col relative overflow-hidden w-full shadow-2xl">
                    <div class="absolute -right-16 -top-16 w-36 h-36 bg-white/2 rounded-full blur-3xl pointer-events-none"></div>
                    <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-widest mb-6 flex items-center gap-2 relative z-10">
                        <div class="w-8 h-8 rounded-lg bg-neutral-900 border border-neutral-850 flex items-center justify-center text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.151-.403.521-.667.97-.667s.82.264.97.667l1.643 4.374a.801.801 0 00.61.545l4.636.564c.447.054.627.608.283.918l-3.53 3.178a.803.803 0 00-.236.726l.997 4.542c.096.44-.356.769-.749.53l-4.072-2.473a.802 8.02 0 00-.83 0l-4.072 2.473c-.393.238-.845-.09-.749-.53l.997-4.542a.803.803 0 00-.236-.726l-3.53-3.178c-.344-.31-.164-.864.283-.918l4.636-.564a.801.801 0 00.61-.545L11.48 3.5z" />
                            </svg>
                        </div>
                        Platform Feedback
                    </h3>
                    
                    <form action="{{ route('client.feedback.store') }}" method="POST" class="flex flex-col relative z-10" x-data="{ comment: '' }">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2 pl-0.5">Rating Anda</label>
                            <select name="rating" class="input-field font-semibold cursor-pointer">
                                <option value="5" class="bg-neutral-950 text-white">⭐⭐⭐⭐⭐ Sangat Memuaskan</option>
                                <option value="4" class="bg-neutral-950 text-white">⭐⭐⭐⭐ Bagus</option>
                                <option value="3" class="bg-neutral-950 text-white">⭐⭐⭐ Cukup Baik</option>
                                <option value="2" class="bg-neutral-950 text-white">⭐⭐ Kurang Memuaskan</option>
                                <option value="1" class="bg-neutral-950 text-white">⭐ Mengecewakan</option>
                            </select>
                        </div>
                        
                        <!-- Premium Preset Feedback Chips -->
                        <div class="mb-5">
                            <label class="block text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-2.5 pl-0.5">Pilih Cepat</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="comment = 'Sangat memuaskan dan mudah digunakan!'" class="text-[10px] font-bold uppercase tracking-wider bg-neutral-950 border border-neutral-900 hover:border-neutral-700 text-neutral-300 rounded-full px-4 py-2 transition-all active:scale-[0.96] cursor-pointer">Sangat Memuaskan</button>
                                <button type="button" @click="comment = 'Harganya sepadan dengan fitur yang didapat.'" class="text-[10px] font-bold uppercase tracking-wider bg-neutral-950 border border-neutral-900 hover:border-neutral-700 text-neutral-300 rounded-full px-4 py-2 transition-all active:scale-[0.96] cursor-pointer">Harga Sepadan</button>
                                <button type="button" @click="comment = 'Servernya cepat dan stabil, tanpa kendala.'" class="text-[10px] font-bold uppercase tracking-wider bg-neutral-950 border border-neutral-900 hover:border-neutral-700 text-neutral-300 rounded-full px-4 py-2 transition-all active:scale-[0.96] cursor-pointer">Cepat & Stabil</button>
                                <button type="button" @click="comment = 'Proses deploy sangat gampang untuk pemula.'" class="text-[10px] font-bold uppercase tracking-wider bg-neutral-950 border border-neutral-900 hover:border-neutral-700 text-neutral-300 rounded-full px-4 py-2 transition-all active:scale-[0.96] cursor-pointer">Mudah untuk Pemula</button>
                            </div>
                        </div>

                        <div class="mb-5 flex-1">
                            <label class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2 pl-0.5">Catatan Tambahan</label>
                            <textarea name="comment" x-model="comment" rows="3" class="input-field w-full resize-none placeholder-neutral-600 text-xs sm:text-sm font-semibold" placeholder="Tulis masukan Anda di sini..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full btn-primary h-12 uppercase tracking-wider font-extrabold text-xs active:scale-[0.98] cursor-pointer">
                            Kirim Feedback
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <!-- Fullscreen Backdrop-Blurred Stepper Overlay for Deprovisioning -->
    <div id="deprovision-overlay" class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-black/95 backdrop-blur-xl opacity-0 pointer-events-none transition-opacity duration-300 select-none">
        <!-- Accent Glow Ring -->
        <div class="absolute w-[500px] h-[500px] rounded-full bg-red-950/20 blur-[130px] -top-30 -left-30 pointer-events-none"></div>

        <div class="relative z-10 max-w-md w-full px-6 flex flex-col items-center">
            
            <!-- Icon Status -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-red-950/30 border border-red-900/30 rounded-2xl flex items-center justify-center mb-4 mx-auto animate-pulse">
                    <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white tracking-tight">Deprovisioning Subdomain</h3>
                <p class="text-neutral-450 text-xs mt-1.5 font-medium">Deallocating secure filesystems, drop schemes, and web hosts...</p>
            </div>

            <!-- Stepper Container -->
            <div class="w-full bg-neutral-950 border border-neutral-900 rounded-2xl p-6 mb-6 space-y-6 relative overflow-hidden">
                <!-- Connect Progress Line -->
                <div class="absolute left-7 top-[38px] bottom-[38px] w-0.5 bg-neutral-900 pointer-events-none">
                    <div id="deprovision-progress-line" class="w-full h-0 bg-red-650 transition-all duration-[600ms]"></div>
                </div>

                <!-- Step 1 -->
                <div class="flex items-start gap-4 relative z-10" id="deprovision-step-1">
                    <div class="step-icon w-8 h-8 rounded-full bg-neutral-950 border-2 border-neutral-900 flex items-center justify-center text-xs font-extrabold text-neutral-500 transition-all shrink-0">
                        1
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-neutral-500 transition-colors step-title uppercase tracking-wider">Hapus Virtual Host</h4>
                        <p class="text-[10px] text-neutral-500 font-semibold mt-0.5 step-desc">Menghapus vhost server dan DNS bindings.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start gap-4 relative z-10" id="deprovision-step-2">
                    <div class="step-icon w-8 h-8 rounded-full bg-neutral-950 border-2 border-neutral-900 flex items-center justify-center text-xs font-extrabold text-neutral-500 transition-all shrink-0">
                        2
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-neutral-500 transition-colors step-title uppercase tracking-wider">Drop Database Schema</h4>
                        <p class="text-[10px] text-neutral-500 font-semibold mt-0.5 step-desc">Menghapus schema dan tablespace basis data.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start gap-4 relative z-10" id="deprovision-step-3">
                    <div class="step-icon w-8 h-8 rounded-full bg-neutral-950 border-2 border-neutral-900 flex items-center justify-center text-xs font-extrabold text-neutral-500 transition-all shrink-0">
                        3
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-neutral-500 transition-colors step-title uppercase tracking-wider">Revoke Credentials</h4>
                        <p class="text-[10px] text-neutral-500 font-semibold mt-0.5 step-desc">Mencabut MySQL connection grants dan privileges.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex items-start gap-4 relative z-10" id="deprovision-step-4">
                    <div class="step-icon w-8 h-8 rounded-full bg-neutral-950 border-2 border-neutral-900 flex items-center justify-center text-xs font-extrabold text-neutral-500 transition-all shrink-0">
                        4
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-neutral-500 transition-colors step-title uppercase tracking-wider">Wipe Storage Files</h4>
                        <p class="text-[10px] text-neutral-500 font-semibold mt-0.5 step-desc">Menghapus file deploy ZIP dan doc_root.</p>
                    </div>
                </div>
            </div>

            <!-- Sleek terminal logger -->
            <div class="w-full bg-[#050507] border border-neutral-900 rounded-xl p-4 font-mono text-[10px] text-red-500/80 shadow-inner h-28 overflow-y-auto space-y-1 scrollbar-thin">
                <div class="flex items-center gap-1.5 text-neutral-600">
                    <span>$</span>
                    <span class="text-neutral-400">deprovision --target="{{ $subdomain->full_domain }}"</span>
                </div>
                <div id="deprovision-logs" class="space-y-1"></div>
            </div>
        </div>
    </div>

    <!-- Script Layer -->
    <script>
        let isFileValid = false;

        // Custom responsive file selector display
        function handleFileSelect(input) {
            const fileNameDisplay = document.getElementById('file-chosen-text');
            const fileInfoText = document.getElementById('file-info-text');
            
            const dropzone = document.getElementById('upload-dropzone');
            const defaultState = document.getElementById('default-upload-state');
            const fileSelectedState = document.getElementById('file-selected-state');
            
            const btn = document.getElementById('deploy-btn');
            
            if (input.files.length > 0) {
                const file = input.files[0];
                fileNameDisplay.innerText = file.name;
                
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                const sizeKB = (file.size / 1024).toFixed(2);
                const maxMB = {{ $plan ? $plan->max_storage_mb : 50 }};
                const displaySize = file.size >= 1048576 ? sizeMB + ' MB' : sizeKB + ' KB';
                
                defaultState.classList.add('hidden', 'opacity-0');
                fileSelectedState.classList.remove('hidden');
                fileSelectedState.classList.add('flex');
                
                if (sizeMB > maxMB) {
                    fileInfoText.innerHTML = `<span class="text-red-400 font-bold">Terlalu besar: ${displaySize} / ${maxMB} MB Limit</span>`;
                    dropzone.classList.add('border-red-500', 'bg-red-500/5');
                    dropzone.classList.remove('border-neutral-850', 'border-neutral-500');
                    if (btn) btn.disabled = true;
                } else {
                    fileInfoText.innerText = displaySize;
                    dropzone.classList.add('border-white', 'bg-white/2');
                    dropzone.classList.remove('border-neutral-850', 'border-red-500');
                    if (btn) btn.disabled = false;
                    isFileValid = true;
                }
            } else {
                isFileValid = false;
                cancelUpload();
            }
        }

        function cancelUpload() {
            const input = document.getElementById('zip_file');
            input.value = '';
            
            const dropzone = document.getElementById('upload-dropzone');
            const defaultState = document.getElementById('default-upload-state');
            const fileSelectedState = document.getElementById('file-selected-state');
            const btn = document.getElementById('deploy-btn');

            defaultState.classList.remove('hidden', 'opacity-0');
            fileSelectedState.classList.add('hidden');
            fileSelectedState.classList.remove('flex');
            
            dropzone.classList.remove('border-white', 'bg-white/2', 'border-red-500', 'bg-red-500/5');
            dropzone.classList.add('border-neutral-850');
            
            if (btn) btn.disabled = true;
        }

        // Stepper animation overlays for deprovisioning
        window.startDeprovisioningSteps = function(formElement) {
            const overlay = document.getElementById('deprovision-overlay');
            if (overlay && overlay.parentNode !== document.body) {
                document.body.appendChild(overlay);
            }

            const logsContainer = document.getElementById('deprovision-logs');
            const progressLine = document.getElementById('deprovision-progress-line');

            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');

            const addLog = (text, type = 'info') => {
                const log = document.createElement('div');
                log.className = 'flex items-start gap-1.5';
                const time = new Date().toLocaleTimeString('en-US', { hour12: false });
                
                let textColor = 'text-red-500/80';
                if (type === 'success') textColor = 'text-white font-semibold';
                if (type === 'accent') textColor = 'text-neutral-350';

                log.innerHTML = `<span class="text-neutral-700">[${time}]</span> <span class="${textColor}">${text}</span>`;
                logsContainer.appendChild(log);
                logsContainer.scrollTop = logsContainer.scrollHeight;
            };

            const setStepState = (stepNumber, state) => {
                const stepEl = document.getElementById(`deprovision-step-${stepNumber}`);
                if (!stepEl) return;

                const icon = stepEl.querySelector('.step-icon');
                const title = stepEl.querySelector('.step-title');
                const desc = stepEl.querySelector('.step-desc');

                if (state === 'active') {
                    icon.className = 'step-icon w-8 h-8 rounded-full bg-red-950 border-2 border-red-500 flex items-center justify-center text-xs font-bold text-red-400 transition-all shrink-0 shadow-[0_0_15px_rgba(239,68,68,0.4)]';
                    icon.innerHTML = `<svg class="animate-spin w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                    title.className = 'text-xs font-bold text-white transition-colors step-title uppercase tracking-wider';
                } else if (state === 'success') {
                    icon.className = 'step-icon w-8 h-8 rounded-full bg-red-600 border-2 border-red-500 flex items-center justify-center text-xs font-bold text-white transition-all shrink-0 scale-105';
                    icon.innerHTML = `<svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>`;
                    title.className = 'text-xs font-bold text-red-500 transition-colors step-title uppercase tracking-wider';
                    desc.className = 'text-[10px] text-neutral-450 transition-colors step-desc font-semibold';
                }
            };

            // Simulate deprovision steps
            setTimeout(() => {
                setStepState(1, 'active');
                addLog('Menginisialisasi deprovisioning paket...');
                addLog('Menghubungi Virtual Host API server...');
            }, 500);

            setTimeout(() => {
                setStepState(1, 'success');
                progressLine.style.height = '33%';
                addLog('Konfigurasi Virtual Host berhasil dihapus.', 'success');
                addLog('Subdomain dinonaktifkan di DNS zone file.', 'success');
                
                setStepState(2, 'active');
                addLog('Memulai deprovisioning database...');
                addLog('Mencari database schema: "subly_db_{{ $subdomain->name }}"...');
            }, 2300);

            setTimeout(() => {
                setStepState(2, 'success');
                progressLine.style.height = '66%';
                addLog('MySQL Database berhasil di-drop/dihapus.', 'success');
                addLog('Semua tabel & data terhapus permanen.', 'success');
                
                setStepState(3, 'active');
                addLog('Mengidentifikasi MySQL User: "subly_u_{{ $subdomain->name }}"...');
                addLog('Mencabut (REVOKE) seluruh hak akses database...');
            }, 4100);

            setTimeout(() => {
                setStepState(3, 'success');
                progressLine.style.height = '100%';
                addLog('Database user berhasil dihapus dari server.', 'success');
                
                setStepState(4, 'active');
                addLog('Membersihkan folder deployment: "{{ $subdomain->doc_root }}"...');
                addLog('Menghapus ZIP file arsip dari cloud storage...');
            }, 5900);

            setTimeout(() => {
                setStepState(4, 'success');
                addLog('Semua folder & file deployment berhasil dibersihkan.', 'success');
                addLog('Deprovisioning sukses! Mengalihkan halaman...', 'success');
                
                setTimeout(() => {
                    formElement.submit();
                }, 800);
            }, 7700);
        };

        // Chunked zip upload mechanism
        async function submitDeployment(event, hasWarning) {
            const fileInput = document.getElementById('zip_file');
            const notesInput = document.getElementById('notes');
            
            if (fileInput.files.length === 0) {
                alert('Pilih file ZIP terlebih dahulu!');
                return;
            }

            if (!isFileValid) {
                alert('File yang dipilih melebihi batas ukuran plan Anda!');
                return;
            }

            if (hasWarning) {
                if (!confirm('PERINGATAN: Mengupload file baru akan menimpa dan menghapus file/situs Anda yang sebelumnya. Apakah Anda yakin ingin melanjutkan?')) {
                    return;
                }
            }
            
            const file = fileInput.files[0];
            const chunkSize = 2 * 1024 * 1024; // 2MB chunks
            const totalChunks = Math.ceil(file.size / chunkSize);
            const uploadId = 'up_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            const subdomainId = {{ $subdomain->id }};
            const notes = notesInput ? notesInput.value : '';

            const btn = document.getElementById('deploy-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            const progressContainer = document.getElementById('progress-container');
            const progressBar = document.getElementById('progress-bar');
            const progressPercent = document.getElementById('progress-percent');
            const progressLabel = document.getElementById('progress-label');

            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
            spinner.classList.remove('hidden');
            btnText.innerText = "Processing...";
            progressContainer.classList.remove('hidden');

            for (let i = 0; i < totalChunks; i++) {
                const start = i * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('chunk', chunk);
                formData.append('upload_id', uploadId);
                formData.append('chunk_index', i);
                formData.append('total_chunks', totalChunks);
                formData.append('file_name', file.name);
                formData.append('subdomain_id', subdomainId);
                formData.append('notes', notes);
                formData.append('_token', '{{ csrf_token() }}');

                progressLabel.innerText = `Uploading Part ${i + 1} of ${totalChunks}...`;
                
                try {
                    const response = await fetch('{{ route('client.deployments.upload-chunk') }}', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.error || 'Upload failed');
                    }

                    const percent = Math.round(((i + 1) / totalChunks) * 100);
                    progressBar.style.width = percent + '%';
                    progressPercent.innerText = percent + '%';

                    if (i === totalChunks - 1) {
                        btnText.innerText = "Deploying...";
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } catch (error) {
                    console.error('Upload Error:', error);
                    alert('Error during upload: ' + error.message);
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    spinner.classList.add('hidden');
                    btnText.innerText = "Retry Deployment";
                    return;
                }
            }
        }
    </script>
</x-app-layout>
