<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            Notifikasi
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 select-none" x-data="{ 
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl overflow-hidden shadow-2xl relative">
                <div class="p-6 border-b border-neutral-900 bg-neutral-950/20 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-bold text-white tracking-tight uppercase tracking-wider">Riwayat Notifikasi</h3>
                        <p class="text-xs text-neutral-450 mt-1 font-medium">Daftar semua pemberitahuan yang dikirimkan kepada Anda secara berkala.</p>
                    </div>
                    <div class="flex gap-2.5">
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.readAll') }}">
                                @csrf
                                <button type="submit" class="p-2.5 bg-neutral-950 hover:bg-neutral-900 text-neutral-400 hover:text-white rounded-xl border border-neutral-850 active:scale-[0.96] transition-all cursor-pointer" title="Mark all as read">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                        @if(Auth::user()->notifications->count() > 0)
                            <form method="POST" action="{{ route('notifications.clearAll') }}" onsubmit="return confirm('Hapus semua notifikasi?')">
                                @csrf
                                <button type="submit" class="p-2.5 bg-red-950/20 text-red-400 hover:bg-red-900 hover:text-white rounded-xl border border-red-900/30 active:scale-[0.96] transition-all cursor-pointer" title="Clear all notifications">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="table-container">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-neutral-950/80">
                                <th class="table-th">Waktu</th>
                                <th class="table-th">Isi Pesan</th>
                                <th class="table-th text-center">Status</th>
                                <th class="table-th text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-900/50">
                            @forelse($notifications as $notification)
                                <tr class="group hover:bg-neutral-900/10 transition-colors {{ is_null($notification->read_at) ? 'bg-white/2' : '' }}">
                                    <td class="table-td">
                                        <div class="text-xs font-semibold text-neutral-200">{{ $notification->created_at->diffForHumans() }}</div>
                                        <div class="text-[10px] text-neutral-550 mt-0.5 font-medium">{{ $notification->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="table-td min-w-[250px]">
                                        <div class="text-xs text-neutral-350 leading-relaxed max-w-md truncate font-medium group-hover:whitespace-normal group-hover:overflow-visible transition-all duration-300">
                                            {{ $notification->data['message'] ?? 'Pesan tidak tersedia' }}
                                        </div>
                                    </td>
                                    <td class="table-td text-center">
                                        @if(is_null($notification->read_at))
                                            <span class="px-2 py-0.5 inline-flex text-[9px] font-bold uppercase tracking-wider rounded-md border bg-neutral-900 border-neutral-850 text-white shadow-[0_0_10px_rgba(255,255,255,0.05)]">
                                                Baru
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 inline-flex text-[9px] font-bold uppercase tracking-wider rounded-md border bg-neutral-900/40 border-neutral-900 text-neutral-550">
                                                Dibaca
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-td text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <button 
                                                @click="openModal({{ Js::from($notification->data['message'] ?? '') }}, '{{ $notification->created_at->format('d M Y H:i') }}')"
                                                class="btn-secondary h-8 px-3 text-[10px] uppercase font-extrabold tracking-wider inline-flex active:scale-95"
                                            >
                                                Baca
                                            </button>
                                            
                                            @if(is_null($notification->read_at))
                                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                    @csrf
                                                    <button type="submit" class="p-1.5 text-neutral-500 hover:text-white hover:bg-neutral-900 rounded-xl transition-all cursor-pointer" title="Mark as read">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-neutral-500 hover:text-red-400 hover:bg-red-500/5 rounded-xl transition-all cursor-pointer" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-neutral-550 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                            </svg>
                                            <p class="text-neutral-500 text-xs font-bold uppercase tracking-widest">Belum ada notifikasi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($notifications->hasPages())
                    <div class="p-6 border-t border-neutral-900 bg-neutral-950/20">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Sleek Backdrop-Blurred Detail Modal -->
        <div 
            x-show="showModal" 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 select-none"
            x-cloak
        >
            <!-- Backdrop -->
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-250"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/75 backdrop-blur-sm"
                @click="showModal = false"
            ></div>

            <!-- Modal Content Card -->
            <div 
                x-show="showModal"
                x-transition:enter="ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative w-full max-w-lg bg-neutral-950 border border-neutral-900 rounded-2xl shadow-2xl overflow-hidden"
            >
                <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-neutral-800 to-transparent"></div>
                
                <div class="p-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-tight" x-text="modalTitle"></h3>
                            <p class="text-[10px] text-neutral-500 font-bold uppercase tracking-wider mt-1.5" x-text="modalTime"></p>
                        </div>
                        <button @click="showModal = false" class="p-1.5 text-neutral-500 hover:text-white hover:bg-neutral-900 rounded-lg transition-all active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="bg-black/60 rounded-xl p-5 border border-neutral-900 min-h-[120px]">
                        <p class="text-neutral-300 leading-relaxed whitespace-pre-line text-xs sm:text-sm font-medium" x-text="modalMessage"></p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button 
                            @click="showModal = false"
                            class="btn-secondary h-10 px-5 text-xs font-bold uppercase tracking-wider active:scale-95 cursor-pointer"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
