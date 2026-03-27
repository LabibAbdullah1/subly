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
            
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <!-- Preview Section -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Current QRIS Preview</label>
                    <div class="relative group aspect-square max-w-[280px] bg-white rounded-2xl overflow-hidden p-4 shadow-2xl border border-gray-800">
                        <img id="qris-preview" src="{{ $qrisImage && strpos($qrisImage, 'images/') === 0 ? asset($qrisImage) : asset('storage/' . $qrisImage) }}" 
                             alt="QRIS Current" class="w-full h-full object-contain">
                    </div>
                    <p class="text-xs text-gray-500 italic">This image will be shown to users during payment.</p>
                </div>

                <!-- Upload Section -->
                <form action="{{ route('admin.settings.update_qris') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Upload New Image</label>
                        <div class="relative group">
                            <input type="file" name="qris_image" id="qris_input" class="hidden" accept="image/*" onchange="previewFile(this)">
                            <label for="qris_input" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-700 rounded-2xl cursor-pointer bg-gray-900/50 hover:bg-gray-800/50 hover:border-primary-500/50 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-500 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or WebP (Max. 2MB)</p>
                                </div>
                            </label>
                        </div>
                        @error('qris_image')
                            <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="new-preview-container" class="hidden animate-fade-in">
                        <label class="block text-sm font-medium text-gray-400 mb-2">New Image Preview</label>
                        <div class="w-24 h-24 bg-white rounded-lg p-2 border border-primary-500/50">
                            <img id="new-qris-preview" src="#" alt="New QRIS" class="w-full h-full object-contain">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-500 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg shadow-primary-500/20 flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewFile(input) {
            const container = document.getElementById('new-preview-container');
            const preview = document.getElementById('new-qris-preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-admin-layout>
