<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary-500/10 rounded-lg">
                <svg class="w-6 h-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('App Settings') }}
                </h2>
                <p class="text-sm text-gray-500">Manage your application configuration and assets</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        @if(session('success'))
            <div class="mb-6 p-4 bg-primary-500/10 border border-primary-500/20 rounded-xl text-primary-400 flex items-center gap-3 animate-fade-in text-sm font-medium">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 flex items-center gap-3 animate-fade-in text-sm font-medium">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="glass-panel overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-800/50">
                <h3 class="text-lg font-semibold text-white mb-1">QRIS Payment Image</h3>
                <p class="text-sm text-gray-500">Upload a QR code image to be displayed on the checkout page for clients.</p>
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
                
                <!-- Advanced Preview Section -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Live QRIS Preview</label>
                    <div class="relative group aspect-square max-w-[320px] bg-white rounded-3xl overflow-hidden p-6 shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-gray-800 transition-all duration-500 hover:scale-[1.02]">
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-500/5 to-transparent pointer-events-none"></div>
                        <img :src="imageUrl" 
                             alt="QRIS Preview" class="w-full h-full object-contain relative z-10 transition-all duration-500"
                             :class="{'scale-95 opacity-50': !imageUrl}">
                        
                        <div x-show="!imageUrl" class="absolute inset-0 flex items-center justify-center text-gray-400">
                           <p class="text-sm">No image selected</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 italic">
                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        This is how your QR code will look to users.
                    </div>
                </div>

                <!-- Interactive Upload Section -->
                <form action="{{ route('admin.settings.update_qris') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-3">Update QRIS Image</label>
                        <div class="relative group">
                            <input type="file" name="qris_image" id="qris_input" class="hidden" accept="image/*" @change="fileChosen">
                            <label for="qris_input" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-700 rounded-3xl cursor-pointer bg-gray-900/30 hover:bg-gray-800/40 hover:border-primary-500/50 transition-all group overflow-hidden relative">
                                <div class="absolute inset-0 bg-primary-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10">
                                    <div class="p-4 rounded-2xl bg-gray-800 mb-3 group-hover:bg-primary-500/20 group-hover:text-primary-400 transition-all">
                                        <svg class="w-8 h-8 text-gray-500 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="mb-2 text-sm text-gray-300"><span class="font-bold text-primary-400">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or WebP (Max. 2MB)</p>
                                </div>
                            </label>
                        </div>
                        @error('qris_image')
                            <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-500 text-white font-bold py-4 px-6 rounded-2xl transition-all shadow-[0_10px_30px_rgba(94,106,210,0.3)] flex items-center justify-center gap-3 group active:scale-[0.98]">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Confirm & Update
                        </button>
                    </div>
                </form>
            </div>
</x-admin-layout>
