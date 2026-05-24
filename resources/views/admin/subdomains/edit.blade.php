<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subdomains.index') }}" class="text-neutral-400 hover:text-white transition-all duration-200 active:scale-[0.94]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Edit Subdomain') }}: <span class="text-white font-mono">{{ $subdomain->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-3xl mx-auto select-none px-4 sm:px-0 space-y-6">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="mb-6">
                <h3 class="text-base font-bold text-white tracking-wide">Perbarui Host Subdomain</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Ubah target klien, jalur direktori, dan konfigurasi status.</p>
            </div>

            <form action="{{ route('admin.subdomains.update', $subdomain) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <x-input-label for="user_id" :value="__('Pemilik (Klien)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="user_id" id="user_id" class="input-field mt-1.5 block w-full text-xs" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (old('user_id', $subdomain->user_id) == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="name" :value="__('Nama Subdomain')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <div class="flex items-center gap-2 mt-1.5 font-semibold">
                            <x-text-input type="text" name="name" id="name" :value="old('name', $subdomain->name)" 
                                class="flex-1 block w-full font-mono text-xs" 
                                placeholder="proyek-saya-yang-keren" required />
                            <span class="text-neutral-500 font-mono text-xs select-none">{{ config('app.subdomain_suffix') }}</span>
                        </div>
                        <p class="text-[10px] text-neutral-500 font-semibold italic mt-1.5">Mengubah ini akan memperbarui jalur dokumen cPanel. Gunakan dengan hati-hati untuk lingkungan yang aktif.</p>
                        @error('name') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="doc_root" :value="__('Folder Dokumen Root (Root Asli)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input type="text" name="doc_root" id="doc_root" :value="old('doc_root', $subdomain->doc_root)" 
                            class="mt-1.5 block w-full font-mono text-xs" 
                            placeholder="/home/username/public_html/subdomain" required />
                        <p class="text-[10px] text-neutral-500 font-semibold italic mt-1.5">Jalur absolut ke direktori root subdomain pada server.</p>
                        @error('doc_root') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Status')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="status" id="status" class="input-field mt-1.5 block w-full text-xs" required>
                            <option value="active" {{ old('status', $subdomain->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $subdomain->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="pt-5 border-t border-neutral-900/60 flex justify-end gap-3">
                    <x-secondary-button type="button" onclick="window.history.back()">
                        Batal
                    </x-secondary-button>
                    <x-primary-button>
                        Perbarui Subdomain
                    </x-primary-button>
                </div>
            </form>
        </div>
        
        <!-- Technical Details Card -->
        <div class="glass-panel p-5 border-neutral-900 border-l-2 border-l-white">
             <h4 class="text-xs font-bold text-white tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Referensi Teknis
             </h4>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mt-3.5 select-all">
                <div>
                    <span class="text-neutral-500 font-semibold uppercase tracking-wider text-[10px] block mb-1">Jalur Dokumen Root:</span>
                    <code class="text-[11px] font-bold text-white font-mono bg-neutral-950 px-2.5 py-1.5 rounded-lg border border-neutral-900 block truncate">{{ $subdomain->doc_root }}</code>
                </div>
                <div>
                    <span class="text-neutral-500 font-semibold uppercase tracking-wider text-[10px] block mb-1">Tautan Domain Eksternal:</span>
                    <a href="https://{{ $subdomain->full_domain }}" target="_blank" class="text-[11px] font-bold text-white font-mono hover:underline bg-neutral-950 px-2.5 py-1.5 rounded-lg border border-neutral-900 flex items-center gap-1">
                        https://{{ $subdomain->full_domain }}
                        <svg class="w-3.5 h-3.5 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
             </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const docRootInput = document.getElementById('doc_root');
            const prefix = "{{ config('app.doc_root_prefix') }}";
            
            if (nameInput && docRootInput) {
                let isManuallyEdited = false;
                
                if (docRootInput.value !== prefix + nameInput.value) {
                    isManuallyEdited = true;
                }
                
                docRootInput.addEventListener('input', function() {
                    isManuallyEdited = true;
                });
                
                nameInput.addEventListener('input', function() {
                    if (!isManuallyEdited) {
                        docRootInput.value = prefix + nameInput.value;
                    }
                });
            }
        });
    </script>
</x-admin-layout>
