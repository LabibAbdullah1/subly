<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Deployment Queue') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Infrastructure Queue</h1>
            <p class="text-xs text-neutral-500 font-medium">Provision subdomains, extract package templates, and update build versions.</p>
        </div>

        @if (session('success'))
            <div class="bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-950/20 border border-red-900/30 text-red-400 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ $errors->first() }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Active Queue Column -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Active Queue
                    </h3>
                    <span class="text-[10px] font-bold text-neutral-450 bg-neutral-900 border border-neutral-850 px-2 py-0.5 rounded-md tracking-wider uppercase">{{ $pendingDeployments->count() }} Pending</span>
                </div>
                
                <div class="glass-panel overflow-hidden border-neutral-900">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th text-[10px]">Target & Artifact</th>
                                    <th class="table-th text-right text-[10px] pr-6">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/40">
                                @forelse($pendingDeployments as $deployment)
                                    <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                        <td class="table-td">
                                            <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $deployment->subdomain?->full_domain ?? 'Deleted Subdomain' }}</div>
                                            <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">Owner: {{ $deployment->subdomain?->user?->name ?? 'Deleted/Unknown User' }}</div>
                                            <div class="flex items-center gap-2 mt-2 select-none">
                                                <a href="{{ route('admin.deployments.download', $deployment) }}" class="text-[10px] font-bold px-2 py-1 rounded-md bg-neutral-900 border border-neutral-850 text-neutral-300 hover:text-white hover:border-neutral-700 transition-all flex items-center gap-1.5 active:scale-[0.98]">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    v{{ $deployment->version }} ZIP
                                                </a>
                                                <span class="text-[10px] text-neutral-500 font-semibold italic">{{ $deployment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td class="table-td text-right pr-6">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.deployments.setup_db', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] rounded-lg bg-neutral-900 border border-neutral-850 hover:border-neutral-700 text-neutral-300 hover:text-white px-2.5 py-1.5 transition-all font-bold active:scale-[0.96] cursor-pointer" title="Setup Virtual Host & Database">
                                                        Provision Server
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.deployments.extract', $deployment) }}" method="POST" class="inline-block" onsubmit="confirm('Are you sure you want to extract and publish this build template to the server?')">
                                                    @csrf
                                                    <button type="submit" class="text-[10px] rounded-lg bg-white text-black px-2.5 py-1.5 hover:bg-neutral-200 transition-all font-bold flex items-center gap-1 active:scale-[0.96] cursor-pointer" title="Extract ZIP on Server and Deploy Live">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        Deploy Live
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf @method('PUT')
                                                    <select name="status" class="text-[10px] rounded-lg bg-neutral-950 border border-neutral-900 text-neutral-300 hover:bg-neutral-900 focus:ring-neutral-700 py-1 pl-1 pr-4 sm:pl-2 sm:pr-6 shadow-inner cursor-pointer font-bold" onchange="this.form.submit()">
                                                        <option value="queued" {{ $deployment->status == 'queued' ? 'selected' : '' }}>Queued</option>
                                                        <option value="processing" {{ $deployment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                        <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Success</option>
                                                        <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Error</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">
                                            No active deployments.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Deployment History Column -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-neutral-450 uppercase tracking-wider flex items-center gap-2 px-2">
                    <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Deployment History
                </h3>

                <div class="glass-panel overflow-hidden border-neutral-900">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th text-[10px]">Artifact</th>
                                    <th class="table-th text-[10px]">Status</th>
                                    <th class="table-th text-right text-[10px] pr-6">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/40">
                                @forelse($completedDeployments as $deployment)
                                    <tr class="hover:bg-neutral-900/20 transition-all duration-350 group/row">
                                        <td class="table-td px-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-xs font-bold text-neutral-200 group-hover/row:text-white transition-colors">{{ $deployment->subdomain?->full_domain ?? 'Deleted Subdomain' }}</div>
                                                    <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">Owner: {{ $deployment->subdomain?->user?->name ?? 'Deleted/Unknown User' }}</div>
                                                    <div class="text-[10px] text-neutral-500 font-semibold mt-1 font-mono">Build v{{ $deployment->version }}</div>
                                                </div>
                                                <a href="{{ route('admin.deployments.download', $deployment) }}" class="opacity-0 group-hover/row:opacity-100 transition-opacity p-2 border border-neutral-850 hover:border-neutral-700 bg-neutral-900 hover:bg-neutral-950 text-neutral-400 hover:text-white rounded-lg active:scale-[0.94] shrink-0 ml-4" title="Download ZIP">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="inline-block">
                                                    @csrf @method('PUT')
                                                    <select name="status" class="text-[10px] uppercase tracking-wider font-bold rounded-lg border-none px-1.5 py-1 cursor-pointer
                                                        {{ $deployment->status == 'success' ? 'bg-neutral-900 text-neutral-350' : 'bg-red-950/30 text-red-400 border border-red-900/10' }}" onchange="this.form.submit()">
                                                        <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Success</option>
                                                        <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Error</option>
                                                        <option value="queued">Restore To Queue</option>
                                                    </select>
                                                </form>
                                                <form action="{{ route('admin.deployments.destroy', $deployment) }}" method="POST" class="inline-block" onsubmit="confirm('Are you sure you want to delete this deployment? This deletes the ZIP file and cannot be undone.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-neutral-600 hover:text-red-400 opacity-0 group-hover/row:opacity-100 transition-all p-1.5 rounded hover:bg-neutral-900/50 cursor-pointer" title="Delete Deployment">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="table-td text-right text-[10px] text-neutral-500 font-semibold pr-6">
                                            {{ $deployment->updated_at->format('d M, H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">History is empty.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($completedDeployments->hasPages())
                        <div class="p-3 border-t border-neutral-900 bg-neutral-950/40">
                            {{ $completedDeployments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
