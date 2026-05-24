<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Pengaturan Global') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-4xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Konfigurasi Aplikasi</h1>
            <p class="text-xs text-neutral-500 font-medium">Perbarui aset gambar sistem pembayaran dan parameter kode QRIS.</p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-neutral-900 border border-neutral-850 rounded-xl text-neutral-250 flex items-center gap-3 animate-fade-in text-xs font-semibold">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
            <div x-init="window.showToast('{{ session('success') }}', 'success')"></div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-950/20 border border-red-900/30 rounded-xl text-red-450 flex items-center gap-3 animate-fade-in text-xs font-semibold">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="glass-panel overflow-hidden border-neutral-900">
            <div class="p-6 sm:p-8 border-b border-neutral-900 bg-neutral-950/40">
                <h3 class="text-base font-bold text-white mb-1">Gambar Invoice Pembayaran QRIS</h3>
                <p class="text-xs text-neutral-500 font-medium">Unggah gambar kode QR untuk ditampilkan secara global di halaman tagihan scanner QRIS untuk klien.</p>
            </div>
            
            <div x-data="{ 
                imageUrl: '{{ $qrisImage && (strpos($qrisImage, 'images/') === 0 || strpos($qrisImage, 'uploads/') === 0) ? asset($qrisImage) : asset('storage/' . $qrisImage) }}',
                fileChosen(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.imageUrl = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
            }" class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                
                <!-- Live QRIS Code Preview -->
                <div class="space-y-4">
                    <label class="block text-[11px] font-bold text-neutral-450 uppercase tracking-wider mb-2">Pratinjau QRIS Langsung</label>
                    <div class="relative group max-w-[280px] bg-white rounded-2xl overflow-hidden p-2.5 shadow-2xl border border-neutral-900 transition-all duration-300 hover:scale-[1.01]">
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent pointer-events-none"></div>
                        <img :src="imageUrl" 
                             alt="QRIS Preview" class="w-full h-auto object-contain relative z-10 rounded-lg transition-all duration-300"
                             :class="{'scale-95 opacity-50': !imageUrl}">
                        
                        <div x-show="!imageUrl" class="absolute inset-0 flex items-center justify-center text-neutral-500">
                           <p class="text-xs font-semibold">Tidak ada gambar dipilih</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] text-neutral-500 font-bold uppercase tracking-wider">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tampilan Checkout Tagihan Global
                    </div>
                </div>

                <!-- Interactive Upload Form -->
                <form action="{{ route('admin.settings.update_qris') }}" method="POST" enctype="multipart/form-data" class="space-y-6 w-full">
                    @csrf
                    <div class="space-y-3">
                        <label class="block text-[11px] font-bold text-neutral-450 uppercase tracking-wider">Unggah File Gambar Baru</label>
                        <div class="relative group">
                            <input type="file" name="qris_image" id="qris_input" class="hidden" accept="image/*" @change="fileChosen">
                            <label for="qris_input" class="flex flex-col items-center justify-center w-full h-48 border border-dashed border-neutral-800 hover:border-neutral-700 rounded-2xl cursor-pointer bg-neutral-950/20 hover:bg-neutral-950/60 transition-all duration-200 overflow-hidden relative active:scale-[0.99]">
                                <div class="absolute inset-0 bg-white/1 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10 px-4 text-center select-none">
                                    <div class="p-3 rounded-xl bg-neutral-900 border border-neutral-850 mb-3 group-hover:text-white transition-all text-neutral-500">
                                        <svg class="w-6 h-6 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="mb-1 text-xs text-neutral-300"><span class="font-bold text-white">Klik untuk mengunggah</span> atau seret dan lepas</p>
                                    <p class="text-[10px] text-neutral-500 font-semibold uppercase tracking-wider">PNG, JPG atau WebP (Maks. 2MB)</p>
                                </div>
                            </label>
                        </div>
                        @error('qris_image')
                            <p class="mt-2 text-xs text-red-400 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full btn-primary py-3 active:scale-[0.98]">
                            Konfirmasi & Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
