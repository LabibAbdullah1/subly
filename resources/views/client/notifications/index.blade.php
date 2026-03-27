<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            {{ __('All Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        showModal: false, 
        modalMessage: '', 
        modalTime: '', 
        modalTitle: 'Detail Notifikasi',
        openModal(msg, time) {
            this.modalMessage = msg;
            this.modalTime = time;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel overflow-hidden relative">
                <div class="p-6 border-b border-gray-800 bg-gray-900/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-100 italic">Riwayat Notifikasi</h3>
                        <p class="text-sm text-gray-400 mt-1">Daftar semua pemberitahuan yang dikirimkan kepada Anda.</p>
                    </div>
                    <div class="flex gap-3">
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button type="submit" class="text-sm px-4 py-2 bg-primary-500/10 text-primary-400 hover:bg-primary-500/20 rounded-xl transition-all border border-primary-500/20">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                        @if(Auth::user()->notifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.clearAll') }}" onsubmit="return confirm('Hapus semua notifikasi?')">
                                @csrf
                                <button type="submit" class="text-sm px-4 py-2 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-xl transition-all border border-red-500/20">
                                    Clear all
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-950/50 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold italic">Waktu</th>
                                <th class="px-6 py-4 font-semibold italic">Isi Pesan</th>
                                <th class="px-6 py-4 font-semibold italic text-center">Status</th>
                                <th class="px-6 py-4 font-semibold italic text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50 font-inter">
                            @forelse($notifications as $notification)
                                <tr class="hover:bg-gray-800/10 transition-all group {{ is_null($notification->read_at) ? 'bg-primary-500/5' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-200 font-medium">{{ $notification->created_at->diffForHumans() }}</div>
                                        <div class="text-[10px] text-gray-500 font-medium">{{ $notification->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 min-w-[250px]">
                                        <div class="text-sm text-gray-300 leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all duration-300">
                                            {{ $notification->data['message'] ?? 'Pesan tidak tersedia' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if(is_null($notification->read_at))
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-primary-500/10 text-primary-400 rounded-lg border border-primary-500/20">
                                                New
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-gray-800 text-gray-500 rounded-lg border border-gray-700/50">
                                                Read
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <button 
                                                @click="openModal({{ Js::from($notification->data['message'] ?? '') }}, '{{ $notification->created_at->format('d M Y H:i') }}')"
                                                class="px-3 py-1.5 text-xs font-semibold bg-primary-500/10 text-primary-400 hover:bg-primary-500/20 rounded-lg border border-primary-500/20 transition-all flex items-center gap-1.5"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                Baca
                                            </button>
                                            
                                            @if(is_null($notification->read_at))
                                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                    @csrf
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-primary-400 hover:bg-primary-500/10 rounded-xl transition-all" title="Mark as read">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-all" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-20 text-center">
                                        <div class="bg-gray-800/50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-600">
                                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                        </div>
                                        <h4 class="text-gray-300 font-bold italic">Belum ada notifikasi</h4>
                                        <p class="text-gray-500 text-sm mt-1">Anda akan melihat pemberitahuan di sini saat tersedia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($notifications->hasPages())
                    <div class="p-6 border-t border-gray-800 bg-gray-900/30">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Notification Modal -->
        <div 
            x-show="showModal" 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            x-cloak
        >
            <!-- Backdrop -->
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                @click="showModal = false"
            ></div>

            <!-- Modal Content -->
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-lg bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl overflow-hidden"
            >
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-600 to-purple-600"></div>
                
                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-white italic" x-text="modalTitle"></h3>
                            <p class="text-xs text-gray-500 mt-1 font-medium" x-text="modalTime"></p>
                        </div>
                        <button @click="showModal = false" class="p-2 text-gray-500 hover:text-white hover:bg-gray-800 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="bg-gray-950/50 rounded-2xl p-6 border border-gray-800/50 min-h-[120px]">
                        <p class="text-gray-300 leading-relaxed whitespace-pre-line text-sm sm:text-base" x-text="modalMessage"></p>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button 
                            @click="showModal = false"
                            class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white text-sm font-semibold rounded-xl border border-gray-700 transition-all"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
