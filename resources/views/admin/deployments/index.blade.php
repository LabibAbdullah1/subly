<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Deployment Queue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3 shadow-lg" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Column 1: Active Queue -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2 px-2">
                        <div class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></div>
                        Active Queue
                        <span class="text-xs font-normal text-gray-500 bg-gray-800 px-2 py-0.5 rounded-full ml-auto">{{ $pendingDeployments->count() }} Pending</span>
                    </h3>
                    
                    <div class="glass-panel overflow-hidden border-primary-500/20 shadow-primary-500/5">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="table-th">Target & Artifact</th>
                                        <th class="table-th text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/50">
                                    @forelse($pendingDeployments as $deployment)
                                        <tr class="group hover:bg-gray-800/20 transition-colors">
                                            <td class="table-td">
                                                <div class="font-medium text-gray-200 text-sm">{{ $deployment->subdomain?->full_domain ?? 'Deleted Subdomain' }}</div>
                                                <div class="text-[11px] text-primary-400 mt-0.5">Owner: {{ $deployment->subdomain?->user?->name ?? 'Deleted/Unknown User' }}</div>
                                                <div class="flex items-center gap-2 mt-2">
                                                    <a href="{{ route('admin.deployments.download', $deployment) }}" class="text-[11px] font-mono px-2 py-0.5 rounded bg-primary-500/10 text-primary-400 border border-primary-500/20 hover:bg-primary-500/20 transition-all flex items-center gap-1.5 group/dl">
                                                        <svg class="w-3 h-3 group-hover/dl:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        v{{ $deployment->version }} ZIP
                                                    </a>
                                                    <span class="text-[10px] text-gray-500 italic">{{ $deployment->created_at->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td class="table-td text-right">
                                                <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf @method('PUT')
                                                    <select name="status" class="text-[10px] sm:text-[11px] rounded bg-gray-950 border-gray-700 text-gray-300 focus:ring-primary-500/50 py-1 pl-1 pr-4 sm:pl-2 sm:pr-6 shadow-inner cursor-pointer" onchange="this.form.submit()">
                                                        <option value="queued" {{ $deployment->status == 'queued' ? 'selected' : '' }}>Queued</option>
                                                        <option value="processing" {{ $deployment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                        <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Success</option>
                                                        <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Error</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="table-td text-center py-10">
                                                <p class="text-sm text-gray-500 italic">No pending deployments.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Recent History -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-400 flex items-center gap-2 px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Deployment History
                    </h3>

                    <div class="glass-panel overflow-hidden border-gray-800 opacity-90 group-hover:opacity-100 transition-opacity">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="table-th">Artifact</th>
                                        <th class="table-th">Status</th>
                                        <th class="table-th text-right opacity-0 md:opacity-100">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/50">
                                    @forelse($completedDeployments as $deployment)
                                        <tr class="hover:bg-gray-800/10 transition-colors group/row">
                                            <td class="table-td px-4">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="text-xs font-medium text-gray-300">{{ $deployment->subdomain?->full_domain ?? 'Deleted Subdomain' }}</div>
                                                        <div class="text-[10px] text-gray-400 mt-0.5">Owner: {{ $deployment->subdomain?->user?->name ?? 'Deleted/Unknown User' }}</div>
                                                        <div class="text-[10px] text-gray-500 mt-1">Build v{{ $deployment->version }}</div>
                                                    </div>
                                                    <a href="{{ route('admin.deployments.download', $deployment) }}" class="opacity-0 group-hover/row:opacity-100 transition-opacity p-1.5 rounded bg-gray-800 text-gray-400 hover:text-primary-400" title="Download ZIP">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    </a>
                                                </div>
                                            <td class="table-td">
                                                <div class="flex items-center gap-2">
                                                    <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="inline-block">
                                                        @csrf @method('PUT')
                                                        <select name="status" class="text-[10px] uppercase tracking-wider font-bold rounded px-1.5 py-0.5 border-none 
                                                            {{ $deployment->status == 'success' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }} shadow-inner cursor-pointer" onchange="this.form.submit()">
                                                            <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Success</option>
                                                            <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Error</option>
                                                            <option value="queued">Restore To Queue</option>
                                                        </select>
                                                    </form>
                                                    <form action="{{ route('admin.deployments.destroy', $deployment) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this deployment? This deletes the ZIP file and cannot be undone.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-gray-500 hover:text-red-400 opacity-0 group-hover/row:opacity-100 transition-all p-1" title="Delete Deployment">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="table-td text-right text-[10px] text-gray-500">
                                                {{ $deployment->updated_at->format('d M, H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="table-td text-center py-10 text-gray-600 italic">History is empty.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($completedDeployments->hasPages())
                            <div class="p-3 border-t border-gray-800/50 bg-gray-900/40">
                                {{ $completedDeployments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
