<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            {{ __('Broadcast Notification') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Card -->
            <div class="glass-panel overflow-hidden" x-data="{ target: 'all', sending: false }">
                <div class="p-6 border-b border-gray-800 bg-gray-900/50">
                    <h3 class="text-lg font-medium text-gray-100 italic">Kirim Pesan ke Client</h3>
                    <p class="text-sm text-gray-400 mt-1">Pesan ini akan muncul di panel notifikasi client secara instan.</p>
                </div>

                <form action="{{ route('admin.notifications.store') }}" method="POST" class="p-8 space-y-6" @submit="sending = true">
                    @csrf

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-primary-500/10 border border-primary-500/20 flex items-center gap-3 animate-fade-in">
                            <div class="bg-primary-500/20 p-2 rounded-lg text-primary-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-100 italic">Berhasil!</p>
                                <p class="text-xs text-gray-400">{{ session('success') }}</p>
                            </div>
                        </div>
                        <div x-init="window.showToast('{{ session('success') }}', 'success')"></div>
                    @endif

                    <!-- Target Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Penerima Notifikasi</label>
                            <div class="flex gap-4 mt-2">
                                <label class="relative flex-1 flex items-center justify-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition-all duration-300 group"
                                    :class="target === 'all' ? 'border-primary-500 bg-primary-500/10 shadow-[0_0_15px_rgba(94,106,210,0.2)]' : 'border-gray-800 bg-gray-950/50 hover:border-gray-700'">
                                    <input type="radio" name="target" value="all" x-model="target" class="hidden">
                                    <div class="w-5 h-5 rounded-full border-2 transition-all flex items-center justify-center"
                                        :class="target === 'all' ? 'border-primary-500 bg-primary-500' : 'border-gray-600'">
                                        <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="target === 'all'"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold transition-colors" :class="target === 'all' ? 'text-white' : 'text-gray-400'">Semua Client</span>
                                        <span class="text-[10px] text-gray-500">Kirim ke seluruh pengguna</span>
                                    </div>
                                </label>

                                <label class="relative flex-1 flex items-center justify-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition-all duration-300 group"
                                    :class="target === 'specific' ? 'border-primary-500 bg-primary-500/10 shadow-[0_0_15px_rgba(94,106,210,0.2)]' : 'border-gray-800 bg-gray-950/50 hover:border-gray-700'">
                                    <input type="radio" name="target" value="specific" x-model="target" class="hidden">
                                    <div class="w-5 h-5 rounded-full border-2 transition-all flex items-center justify-center"
                                        :class="target === 'specific' ? 'border-primary-500 bg-primary-500' : 'border-gray-600'">
                                        <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="target === 'specific'"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold transition-colors" :class="target === 'specific' ? 'text-white' : 'text-gray-400'">Client Spesifik</span>
                                        <span class="text-[10px] text-gray-500">Pilih satu client tertentu</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- User Dropdown (Conditional) -->
                        <div class="space-y-2" x-show="target === 'specific'" x-cloak x-transition>
                            <label for="user_id" class="block text-sm font-medium text-gray-300">Pilih Client</label>
                            <select name="user_id" id="user_id" class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-gray-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                                <option value="">-- Pilih Client --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Message Area -->
                    <div class="space-y-2">
                        <label for="message" class="block text-sm font-medium text-gray-300">Isi Pesan Notifikasi</label>
                        <textarea name="message" id="message" rows="5" required
                            class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-4 text-gray-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all resize-none shadow-inner"
                            placeholder="Ketik pesan yang ingin disampaikan..."></textarea>
                        <p class="text-[11px] text-gray-500 italic">Maksimal 1000 karakter.</p>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end pt-4 border-t border-gray-800/50">
                        <button type="submit" 
                            class="relative flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(94,106,210,0.3)] transition-all hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden group"
                            :disabled="sending">
                            <span x-show="!sending" class="relative z-10 flex items-center gap-2">
                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                Blast Notification
                            </span>
                            <span x-show="sending" class="relative z-10 flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Sending...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview/Tip Card -->
            <div class="mt-8 glass-panel p-6 border-l-4 border-l-primary-500">
                <div class="flex items-start gap-4">
                    <div class="bg-primary-500/10 p-2 rounded-lg text-primary-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-100 italic">Penggunaan Broadcast</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Gunakan fitur ini untuk pengumuman pemeliharaan (maintenance), promo plan baru, atau update status layanan secara massal. Client akan melihat notifikasi ini di bel notifikasi pada dashboard mereka.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
