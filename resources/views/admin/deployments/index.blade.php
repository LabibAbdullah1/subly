<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Deployment Queue') }}
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
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-lg flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-th">Client & Domain</th>
                                <th class="table-th">Build Artifact</th>
                                <th class="table-th">Status</th>
                                <th class="table-th">Time</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($deployments as $deployment)
                                <tr class="group" x-data="{ showSetup: false }">
                                    <td class="table-td">
                                        <div class="font-medium text-gray-200">{{ $deployment->subdomain->full_domain ?? 'N/A' }}</div>
                                        <div class="text-gray-500 text-xs mt-1">{{ $deployment->subdomain->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="table-td">
                                        <a href="{{ Storage::url($deployment->zip_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-primary-500/10 text-primary-400 hover:bg-primary-500/20 border border-primary-500/20 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            v{{ $deployment->version }}
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        <form action="{{ route('admin.deployments.update_status', $deployment) }}" method="POST" class="flex items-center gap-2">
                                            @csrf @method('PUT')
                                            <select name="status" class="text-xs rounded-md border-gray-700 bg-gray-800 text-gray-300 focus:border-primary-500 focus:ring-primary-500/50 py-1.5 pl-3 pr-8 shadow-inner" onchange="this.form.submit()">
                                                <option value="queued" {{ $deployment->status == 'queued' ? 'selected' : '' }}>Queued</option>
                                                <option value="processing" {{ $deployment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="success" {{ $deployment->status == 'success' ? 'selected' : '' }}>Success</option>
                                                <option value="error" {{ $deployment->status == 'error' ? 'selected' : '' }}>Error</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="table-td text-gray-400">
                                        {{ $deployment->created_at->diffForHumans() }}
                                    </td>
                                    <td class="table-td text-right">
                                        <button @click="showSetup = !showSetup" class="inline-flex items-center justify-center px-3 py-1.5 border border-gray-700 rounded-md text-xs font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white transition-colors">
                                            Setup DB
                                        </button>
                                    </td>
                                </tr>
                                <!-- Inline Setup Form -->
                                <tr x-show="showSetup" class="bg-gray-800/20 border-b border-gray-800/50" x-transition.opacity>
                                    <td colspan="5" class="px-6 py-6">
                                        <form action="{{ route('admin.deployments.setup_db', $deployment) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-gray-900/50 p-4 rounded-xl border border-gray-800 shadow-inner">
                                            @csrf
                                            <div class="col-span-1 md:col-span-4 mb-2">
                                                <h4 class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                                    Database Credentials Provisioning
                                                </h4>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-400 mb-1">Database Name</label>
                                                <input type="text" name="db_name" class="block w-full text-sm rounded-lg bg-gray-950 border-gray-800 text-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-inner" required placeholder="subly_clientdb">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-400 mb-1">Database User</label>
                                                <input type="text" name="db_user" class="block w-full text-sm rounded-lg bg-gray-950 border-gray-800 text-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-inner" required placeholder="subly_user">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-400 mb-1">Database Password</label>
                                                <input type="text" name="db_password" class="block w-full text-sm rounded-lg bg-gray-950 border-gray-800 text-gray-200 focus:border-primary-500 focus:ring-primary-500 shadow-inner" required placeholder="SecurePassword123!">
                                            </div>
                                            <div>
                                                <button type="submit" class="w-full btn-primary py-2.5">
                                                    Provision Credentials
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="table-td text-center py-12">
                                        <div class="flex flex-col items-center justify-center text-gray-500 space-y-3">
                                            <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p>No deployments in queue.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">
                    {{ $deployments->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
