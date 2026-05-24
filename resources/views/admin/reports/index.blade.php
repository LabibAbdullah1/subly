<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Support Center') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Help Desk Console</h1>
            <p class="text-xs text-neutral-500 font-medium">Moderate incoming support reports, review priorities, and update client resolution trackers.</p>
        </div>

        @if (session('success'))
            <div class="bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        <div class="glass-panel overflow-hidden border-neutral-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px] w-1/4">Client Identifier</th>
                            <th class="table-th text-[10px] w-1/2">Ticket Details</th>
                            <th class="table-th text-[10px] w-1/4 text-right pr-8">Resolution Tracker</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($reports as $report)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td align-top pt-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-neutral-900 border border-neutral-850 flex items-center justify-center text-xs font-bold text-white uppercase shadow-md select-none">{{ substr($report->user->name, 0, 1) }}</div>
                                        <div>
                                            <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $report->user->name }}</div>
                                            <div class="text-neutral-500 text-[10px] font-semibold mt-0.5">{{ $report->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <div class="text-xs font-bold text-neutral-200 mb-2 flex items-center gap-2">
                                        @if($report->status === 'open')
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                        @elseif($report->status === 'in_progress')
                                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-500"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-800"></span>
                                        @endif
                                        {{ $report->subject }}
                                    </div>
                                    <div class="text-neutral-350 text-xs bg-neutral-950 border border-neutral-900 p-4 rounded-xl leading-relaxed max-w-2xl whitespace-pre-wrap">
                                        {{ $report->message }}
                                    </div>
                                    <div class="text-neutral-500 text-[9px] font-bold uppercase tracking-wider mt-3 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-neutral-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Opened {{ $report->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="table-td align-top pt-5 text-right pr-8">
                                    <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="flex flex-col items-end gap-3">
                                        @csrf @method('PUT')
                                        <select name="status" class="input-field py-1.5 px-3 text-xs w-40 cursor-pointer font-bold">
                                            <option value="open" {{ $report->status == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                        <button type="submit" class="w-40 btn-secondary py-1.5 px-3 text-xs font-bold active:scale-[0.96] flex items-center justify-center gap-1.5 border border-neutral-850 hover:border-neutral-700">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            Update Tracker
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="table-td text-center py-16">
                                    <div class="flex flex-col items-center justify-center text-neutral-500 gap-4">
                                        <div class="w-12 h-12 bg-neutral-900 border border-neutral-850 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7M5 13l4-4m-4 4h14"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-white font-bold text-xs">Inbox Clean</p>
                                            <p class="text-[10px] text-neutral-500 font-semibold mt-1">No support tickets currently require attention.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
