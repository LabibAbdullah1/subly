<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            {{ __('Master Control') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Users -->
                <div class="glass-panel p-6 relative overflow-hidden group hover-lift">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-500/10 rounded-full blur-xl group-hover:bg-primary-500/20 transition-all duration-500"></div>
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-gray-800/80 border border-gray-700/50 text-primary-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Total Clients</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <!-- Queued Deployments -->
                <div class="glass-panel p-6 relative overflow-hidden group hover-lift">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-500/10 rounded-full blur-xl group-hover:bg-yellow-500/20 transition-all duration-500"></div>
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-gray-800/80 border border-gray-700/50 text-yellow-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Deployments</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $queuedDeployments }} <span class="text-xs text-gray-500">Queued</span></p>
                        </div>
                    </div>
                </div>

                <!-- Open Tickets -->
                <div class="glass-panel p-6 relative overflow-hidden group hover-lift">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-500/10 rounded-full blur-xl group-hover:bg-red-500/20 transition-all duration-500"></div>
                    <div class="flex items-center border-b-0">
                        <div class="p-3 rounded-lg bg-gray-800/80 border border-gray-700/50 text-red-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Open Tickets</p>
                            <p class="text-2xl font-bold text-gray-100">{{ $openTickets }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="glass-panel p-6 relative overflow-hidden group hover-lift">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-500/10 rounded-full blur-xl group-hover:bg-green-500/20 transition-all duration-500"></div>
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-gray-800/80 border border-gray-700/50 text-green-400 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Revenue</p>
                            <p class="text-2xl font-bold text-gray-100">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Deployments -->
                <div class="glass-panel overflow-hidden">
                    <div class="p-6 border-b border-gray-800/60 bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-100">Recent Deployments</h3>
                        <a href="{{ route('admin.deployments.index') }}" class="text-sm text-primary-400 hover:text-primary-300">View Queue →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th">Client</th>
                                    <th class="table-th">Subdomain</th>
                                    <th class="table-th text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/50">
                                @forelse($recentDeployments as $deployment)
                                <tr class="group">
                                    <td class="table-td">{{ $deployment->subdomain?->user?->name ?? 'Unknown' }}</td>
                                    <td class="table-td text-primary-400 font-mono text-sm">{{ $deployment->subdomain?->full_domain ?? 'N/A' }}</td>
                                    <td class="table-td text-right">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full 
                                            {{ $deployment->status === 'success' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                            {{ $deployment->status === 'queued' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : '' }}
                                            {{ $deployment->status === 'processing' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                            {{ $deployment->status === 'error' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                        ">
                                            {{ ucfirst($deployment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="table-td text-center py-8">No recent deployments.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Support Tickets -->
                <div class="glass-panel overflow-hidden">
                    <div class="p-6 border-b border-gray-800/60 bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-100">Recent Tickets</h3>
                        <a href="{{ route('admin.reports.index') }}" class="text-sm text-primary-400 hover:text-primary-300">Support Center →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th">Client</th>
                                    <th class="table-th">Subject</th>
                                    <th class="table-th text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/50">
                                @forelse($recentTickets as $ticket)
                                <tr class="group hover:bg-primary-500/5 transition-all duration-300">
                                    <td class="table-td text-gray-200 transition-colors group-hover:text-white">{{ $ticket->user->name ?? 'Deleted User' }}</td>
                                    <td class="table-td truncate max-w-[150px]">{{ $ticket->subject }}</td>
                                    <td class="table-td text-right">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-medium rounded-full 
                                            {{ $ticket->status === 'open' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                            {{ $ticket->status === 'in_progress' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : '' }}
                                            {{ $ticket->status === 'resolved' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="table-td text-center py-8">No open tickets.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
