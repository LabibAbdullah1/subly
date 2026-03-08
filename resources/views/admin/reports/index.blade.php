<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Support Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-th w-1/4">Client Identifier</th>
                                <th class="table-th w-1/2">Ticket Details</th>
                                <th class="table-th w-1/4 text-right">Resolution Tracker</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($reports as $report)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td align-top pt-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-gray-700 to-gray-600 flex items-center justify-center text-xs font-bold text-white uppercase shadow-lg">{{ substr($report->user->name, 0, 1) }}</div>
                                            <div>
                                                <div class="font-medium text-gray-200">{{ $report->user->name }}</div>
                                                <div class="text-gray-500 text-xs">{{ $report->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <div class="font-medium text-gray-200 mb-2 flex items-center gap-2">
                                            @if($report->status === 'open')
                                                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                            @elseif($report->status === 'in_progress')
                                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            @endif
                                            {{ $report->subject }}
                                        </div>
                                        <div class="text-gray-400 text-sm bg-gray-900/50 p-4 rounded-lg border border-gray-800/60 leading-relaxed max-w-2xl whitespace-pre-wrap">
                                            {{ $report->message }}
                                        </div>
                                        <div class="text-gray-500 text-xs mt-3 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Opened {{ $report->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="table-td align-top pt-5 text-right">
                                        <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="flex flex-col items-end gap-3">
                                            @csrf @method('PUT')
                                            <div class="relative w-40">
                                                <select name="status" class="block w-full text-xs rounded-md bg-gray-900 border border-gray-700 text-gray-300 focus:border-primary-500 focus:ring-primary-500/50 py-2 pl-3 pr-8 shadow-inner appearance-none transition-colors hover:border-gray-600 cursor-pointer">
                                                    <option value="open" {{ $report->status == 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                </select>
                                                <!-- Custom select arrow -->
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            </div>
                                            <button type="submit" class="w-40 inline-flex justify-center items-center px-3 py-1.5 bg-gray-800 border border-gray-700 rounded-md text-xs font-semibold text-gray-300 hover:text-white hover:bg-gray-700 hover:border-gray-600 focus:outline-[none] transition-all duration-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Update Status
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="table-td text-center py-16">
                                        <div class="flex flex-col items-center justify-center text-gray-500 space-y-4">
                                            <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center border border-gray-700/50">
                                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M5 13l4-4m-4 4h14"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-gray-400 font-medium">Inbox Zero</p>
                                                <p class="text-sm mt-1">No support tickets currently require attention.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">
                    {{ $reports->links() }}
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
