<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                {{ __('Broadcast History') }}
            </h2>
            <a href="{{ route('admin.notifications.create') }}" class="btn-primary flex items-center gap-2 active:scale-[0.98]">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Create Broadcast
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Broadcast Log History</h1>
            <p class="text-xs text-neutral-500 font-medium">Review and track system broadcast alerts sent to client channels.</p>
        </div>

        <div class="glass-panel overflow-hidden border-neutral-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px]">Time</th>
                            <th class="table-th text-[10px]">Target User</th>
                            <th class="table-th text-[10px]">Message Details</th>
                            <th class="table-th text-[10px] text-right pr-8">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($notifications as $notification)
                            @php
                                $data = json_decode($notification->data, true);
                                $user = \App\Models\User::find($notification->notifiable_id);
                            @endphp
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td">
                                    <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                    <div class="text-[9px] text-neutral-500 font-semibold mt-0.5">{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="table-td">
                                    @if($user)
                                        <div class="text-xs font-bold text-white">{{ $user->name }}</div>
                                        <div class="text-[10px] text-neutral-500 font-medium mt-0.5">{{ $user->email }}</div>
                                    @else
                                        <div class="text-xs text-neutral-500 italic font-semibold">Deleted Account</div>
                                    @endif
                                </td>
                                <td class="table-td">
                                    <div class="text-xs text-neutral-450 leading-relaxed max-w-lg truncate">{{ $data['message'] ?? '-' }}</div>
                                </td>
                                <td class="table-td text-right pr-8">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase tracking-wider rounded-md border
                                        {{ $notification->read_at ? 'bg-neutral-900/40 text-neutral-450 border-neutral-900' : 'bg-white text-black border-transparent' }}">
                                        {{ $notification->read_at ? 'Read' : 'Delivered' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">
                                    No broadcast logs recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($notifications->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
