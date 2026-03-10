<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            {{ __('Hosting Portal - ') }} {{ $subdomain->full_domain }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3 animate-fade-in" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-lg flex items-start gap-3 shadow-lg" role="alert">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-2">
               <a href="{{ route('client.index') }}" class="text-gray-400 hover:text-white flex items-center gap-2 transition-colors text-sm w-fit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
               </a>
            </div>

            <!-- Upload Form -->
            <div class="glass-panel p-6 flex flex-col w-full">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 rounded-lg bg-gray-800 border border-gray-700 text-primary-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-100">Deploy Code</h3>
                </div>
                <form action="{{ route('client.deployments.store') }}" method="POST" class="flex-1 flex flex-col gap-6" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="subdomain_id" value="{{ $subdomain->id }}">
                    
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Project Files (.zip)</label>
                        <div class="flex justify-center h-48 border-2 border-gray-700 border-dashed rounded-xl transition-all relative overflow-hidden group bg-gray-900/20 cursor-pointer hover:border-primary-500/50" id="upload-dropzone" onclick="document.getElementById('zip_file').click()">
                            <!-- Hidden Input -->
                            <input id="zip_file" name="zip_file" type="file" class="hidden" accept=".zip" required onchange="handleFileSelect(this)">
                            
                            <!-- Default State -->
                            <div class="flex flex-col items-center justify-center space-y-2 w-full relative z-10 transition-opacity duration-300" id="default-upload-state">
                                <div id="upload-icon-container" class="transform group-hover:scale-110 transition-transform">
                                    <svg class="mx-auto h-12 w-12 text-gray-500 group-hover:text-primary-400 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-primary-400 group-hover:text-primary-300 transition-colors uppercase tracking-wider">Upload a file</p>
                                    <p class="text-xs text-gray-400 mt-1">atau seret dan lepas file ZIP ke sini</p>
                                </div>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">ZIP Maks {{ $plan ? $plan->max_storage_mb : 50 }}MB</p>
                            </div>

                            <!-- File Selected State -->
                            <div class="hidden absolute inset-0 flex-col items-center justify-center bg-green-500/5 backdrop-blur-sm w-full h-full p-4 z-20" id="file-selected-state">
                                <button type="button" onclick="event.stopPropagation(); cancelUpload()" class="absolute top-3 right-3 text-gray-400 hover:text-white bg-gray-800/80 p-2 rounded-lg border border-gray-700 hover:border-gray-500 transition-all shadow-lg" title="Cancel Upload">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <div class="w-14 h-14 rounded-2xl bg-green-500/20 flex items-center justify-center text-green-400 mb-3 border border-green-500/30 shadow-[0_0_20px_rgba(34,197,94,0.2)]">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-white truncate max-w-[85%] text-center px-4" id="file-chosen-text">filename.zip</p>
                                <p class="text-xs text-green-400 font-bold mt-1.5" id="file-info-text">0.00 MB</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-3 flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-yellow-500/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Batas 3x upload per hari. Versi baru akan ditambahkan ke riwayat.
                        </p>
                    </div>

                    @php $hasActiveDeployment = $subdomain->deployments()->where('status', 'success')->exists(); @endphp
                    <button type="button" id="deploy-btn" onclick="submitDeployment(event, {{ $hasActiveDeployment ? 'true' : 'false' }})" class="w-full btn-primary py-3 transition-all rounded-xl {{ (!$plan) ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:shadow-[0_0_25px_rgba(94,106,210,0.5)] active:scale-[0.98]' }} flex items-center justify-center gap-2" {{ (!$plan) ? 'disabled' : '' }}>
                        <span id="btn-text" class="font-bold tracking-wide uppercase text-sm">{{ !$plan ? 'Subscription Required' : 'Initiate Deployment' }}</span>
                        <svg id="btn-spinner" class="hidden animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Subdomains List & Storage -->
            <div class="glass-panel overflow-hidden flex flex-col w-full mb-6">
                <!-- Hosted Environment Top Section (Same as before) -->
                <div class="p-6 border-b border-gray-800/50 bg-gray-900/30 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        Hosted Environment
                    </h3>
                </div>

                <!-- Plan Storage Info -->
                @if($plan)
                    @php
                        $maxMB = $plan->max_storage_mb;
                        $storagePercent = $maxMB > 0 ? min(100, round(($usedStorageMB / $maxMB) * 100)) : 0;
                        $storageColor = 'bg-primary-500';
                        if ($storagePercent > 80) $storageColor = 'bg-red-500';
                        elseif ($storagePercent > 60) $storageColor = 'bg-yellow-500';
                    @endphp
                    <div class="p-6 border-b border-gray-800/50">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-300">Plan Storage Size</span>
                            <span class="text-xs font-bold text-primary-400 bg-primary-500/10 px-2 py-0.5 rounded border border-primary-500/20">{{ $usedStorageDisplay }} / {{ $plan->max_storage_mb }} MB Used</span>
                        </div>
                        <div class="w-full bg-gray-800 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $storageColor }} h-2.5 rounded-full transition-all duration-700 ease-out shadow-[0_0_10px_rgba(94,106,210,0.3)]" style="width: {{ $storagePercent }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="overflow-x-auto relative w-full border-b border-gray-800/50">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-900/70">
                                <th class="table-th">Domain</th>
                                <th class="table-th text-center">Status</th>
                                <th class="table-th text-center">Expiry</th>
                                <th class="table-th text-center">Latest Build</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            <tr class="group hover:bg-gray-800/30 transition-colors">
                                <td class="table-td">
                                    <a href="https://{{ $subdomain->full_domain }}" target="_blank" class="font-medium text-primary-400 hover:text-primary-300 hover:underline flex items-center gap-1.5 transition-colors">
                                        {{ $subdomain->full_domain }}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </td>
                                <td class="table-td text-center">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-md border 
                                        {{ $subdomain->status == 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20 shadow-[0_0_10px_rgba(34,197,94,0.1)]' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                        {{ ucfirst($subdomain->status) }}
                                    </span>
                                </td>
                                <td class="table-td text-center">
                                    @if($subdomain->expired_at)
                                        <div class="text-sm {{ $subdomain->expired_at->isPast() ? 'text-red-400 font-semibold' : 'text-gray-200' }}">
                                            {{ $subdomain->expired_at->diffForHumans() }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $subdomain->expired_at->format('d M Y') }}</div>
                                    @else
                                        <span class="text-gray-500 italic text-sm">Lifetime/None</span>
                                    @endif
                                </td>
                                <td class="table-td text-center border-none">
                                    @php $latest = $subdomain->deployments->last(); @endphp
                                    @if($latest)
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-xs text-gray-400">v{{ $latest->version }}</span>
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full 
                                                {{ $latest->status === 'success' ? 'bg-green-500/10 text-green-400' : '' }}
                                                {{ $latest->status === 'queued' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                                {{ $latest->status === 'processing' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                                {{ $latest->status === 'error' ? 'bg-red-500/10 text-red-400' : '' }}">
                                                {{ ucfirst($latest->status) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 italic">No deployments yet</span>
                                    @endif
                                </td>
                                <td class="table-td text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('client.subdomains.renew', $subdomain) }}" class="text-sm font-medium text-primary-400 hover:text-primary-300 transition-colors">Perpanjang</a>
                                        <form action="{{ route('client.subdomains.destroy', $subdomain) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin berhenti berlangganan? Subdomain dan seluruh filenya akan dihapus permanen.');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-400 transition-colors">Berhenti</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Feedback -->
            @php $planFeedback = $plan ? $feedbacks->get($plan->id) : null; @endphp
            @if($plan && !$planFeedback)
                <div class="glass-panel p-6 flex flex-col relative overflow-hidden w-full mb-6">
                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <h3 class="text-lg font-medium text-gray-100 mb-6 flex items-center gap-2 relative z-10">
                        <div class="p-1.5 rounded bg-yellow-500/20 text-yellow-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        Platform Feedback
                    </h3>
                    
                    <form action="{{ route('client.feedback.store') }}" method="POST" class="flex flex-col relative" x-data="{ comment: '' }">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Rating Anda</label>
                            <div class="relative">
                                <select name="rating" class="input-field appearance-none pr-10">
                                    <option value="5" class="bg-gray-900">⭐⭐⭐⭐⭐ Sangat Memuaskan</option>
                                    <option value="4" class="bg-gray-900">⭐⭐⭐⭐ Bagus</option>
                                    <option value="3" class="bg-gray-900">⭐⭐⭐ Cukup Baik</option>
                                    <option value="2" class="bg-gray-900">⭐⭐ Kurang Memuaskan</option>
                                    <option value="1" class="bg-gray-900">⭐ Mengecewakan</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Feedback Chips -->
                        <div class="mb-5">
                            <label class="block text-xs font-medium text-gray-500 mb-2 uppercase tracking-wide">Pilih Cepat</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="comment = 'Sangat memuaskan dan mudah digunakan!'" class="text-xs bg-gray-800 hover:bg-primary-500/20 text-gray-300 hover:text-primary-400 border border-gray-700 hover:border-primary-500/50 rounded-full px-3 py-1.5 transition-all">Sangat Memuaskan</button>
                                <button type="button" @click="comment = 'Harganya sepadan dengan fitur yang didapat.'" class="text-xs bg-gray-800 hover:bg-primary-500/20 text-gray-300 hover:text-primary-400 border border-gray-700 hover:border-primary-500/50 rounded-full px-3 py-1.5 transition-all">Harga Sepadan</button>
                                <button type="button" @click="comment = 'Servernya cepat dan stabil, tanpa kendala.'" class="text-xs bg-gray-800 hover:bg-primary-500/20 text-gray-300 hover:text-primary-400 border border-gray-700 hover:border-primary-500/50 rounded-full px-3 py-1.5 transition-all">Cepat & Stabil</button>
                                <button type="button" @click="comment = 'Proses deploy sangat gampang untuk pemula.'" class="text-xs bg-gray-800 hover:bg-primary-500/20 text-gray-300 hover:text-primary-400 border border-gray-700 hover:border-primary-500/50 rounded-full px-3 py-1.5 transition-all">Mudah untuk Pemula</button>
                            </div>
                        </div>

                        <div class="mb-5 flex-1">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Catatan Tambahan</label>
                            <textarea name="comment" x-model="comment" rows="3" class="input-field w-full resize-none text-sm" placeholder="Tulis masukan Anda di sini..."></textarea>
                        </div>
                        <button type="submit" class="w-full btn-secondary py-2.5 border-gray-700 bg-gray-800 hover:bg-primary-600 hover:text-white hover:border-primary-500 mt-auto transition-all text-sm font-medium">
                            Kirim Feedback
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

    <script>
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
                
                // Show file size vs limit
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                const sizeKB = (file.size / 1024).toFixed(2);
                const maxMB = {{ $plan ? $plan->max_storage_mb : 50 }};
                
                // Display text
                const displaySize = file.size >= 1048576 ? sizeMB + ' MB' : sizeKB + ' KB';
                
                // Toggle UI state
                defaultState.classList.add('hidden', 'opacity-0');
                fileSelectedState.classList.remove('hidden');
                fileSelectedState.classList.add('flex');
                
                if(sizeMB > maxMB) {
                    fileInfoText.innerHTML = `<span class="text-red-400 font-bold">Terlalu besar: ${displaySize} / ${maxMB} MB Limit</span>`;
                    dropzone.classList.add('border-red-500', 'bg-red-500/5', 'ring-2', 'ring-red-500/20');
                    dropzone.classList.remove('border-gray-700', 'border-primary-500', 'border-green-500', 'ring-green-500/20');
                    if (btn) btn.disabled = true;
                } else {
                    fileInfoText.innerText = displaySize;
                    dropzone.classList.add('border-green-500', 'bg-green-500/5', 'ring-2', 'ring-green-500/20');
                    dropzone.classList.remove('border-gray-700', 'border-red-500', 'bg-red-500/5', 'ring-red-500/20');
                    if (btn) btn.disabled = false;
                }
            } else {
                cancelUpload();
            }
        }

        function cancelUpload() {
            const input = document.getElementById('zip_file');
            input.value = ''; // clear input
            
            const dropzone = document.getElementById('upload-dropzone');
            const defaultState = document.getElementById('default-upload-state');
            const fileSelectedState = document.getElementById('file-selected-state');
            const btn = document.getElementById('deploy-btn');

            defaultState.classList.remove('hidden', 'opacity-0');
            fileSelectedState.classList.add('hidden');
            fileSelectedState.classList.remove('flex');
            
            dropzone.classList.remove('border-primary-500', 'bg-primary-500/5', 'border-red-500', 'bg-red-500/5');
            dropzone.classList.add('border-gray-700');
            
            if (btn) btn.disabled = true;
        }

        function submitDeployment(event, hasWarning) {
            const input = document.getElementById('zip_file');
            if (input.files.length === 0) {
                event.preventDefault();
                alert('Pilih file ZIP terlebih dahulu!');
                return;
            }

            if (hasWarning) {
                if (!confirm('PERINGATAN: Mengupload file baru akan menimpa dan menghapus file/situs Anda yang sebelumnya. Apakah Anda yakin ingin melanjutkan?')) {
                    event.preventDefault();
                    return;
                }
            }
            
            const form = input.closest('form');
            
            // Set loading state
            const btn = document.getElementById('deploy-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            
            if (btn && btnText && spinner) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.innerText = "Mengupload Project...";
                spinner.classList.remove('hidden');
            }
            form.submit();
        }
    </script>
</x-app-layout>
