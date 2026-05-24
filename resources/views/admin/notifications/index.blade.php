<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                {{ __('Broadcast History') }}
            </h2>
            <a href="{{ route('admin.notifications.create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-primary-500/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Create Broadcast
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel overflow-hidden">
                <div class="p-6 border-b border-gray-800 bg-gray-900/50">
                    <h3 class="text-lg font-medium text-gray-100 italic">Log Notifikasi Terkirim</h3>
                    <p class="text-sm text-gray-400 mt-1">Daftar semua notifikasi yang telah dikirimkan melalui sistem broadcast.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-950/50 text-gray-400 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">Tujuan</th>
                                <th class="px-6 py-4 font-semibold">Isi Pesan</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($notifications as $notification)
                                @php
                                    $data = json_decode($notification->data, true);
                                    $user = \App\Models\User::find($notification->notifiable_id);
                                @endphp
                                <tr class="hover:bg-gray-800/10 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-300 font-medium">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                        <div class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user)
                                            <div class="text-sm text-primary-400 font-bold">{{ $user->name }}</div>
                                            <div class="text-[10px] text-gray-500">{{ $user->email }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">Unknown User</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-300 max-w-md line-clamp-2">{{ $data['message'] ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs">
                                        @if($notification->read_at)
                                            <span class="px-2 py-1 bg-green-500/10 text-green-400 rounded-lg border border-green-500/20">Read</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-500/10 text-yellow-400 rounded-lg border border-yellow-500/20">Delivered</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-20 text-center">
                                        <div class="bg-gray-800/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-600">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                        </div>
                                        <h4 class="text-gray-300 font-medium">Belum ada riwayat broadcast</h4>
                                        <p class="text-gray-500 text-sm mt-1">Broadcast yang Anda kirim akan muncul di sini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($notifications->hasPages())
                    <div class="p-6 border-t border-gray-800 bg-gray-900/30 font-inter">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
