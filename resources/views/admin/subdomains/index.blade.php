<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Subdomain Management') }}
            </h2>
            <a href="{{ route('admin.subdomains.create') }}" class="btn-primary w-full sm:w-auto text-center">
                Register New Subdomain
            </a>
        </div>
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
                                <th class="table-th">Owner</th>
                                <th class="table-th">Subdomain Name</th>
                                <th class="table-th">Full URL</th>
                                <th class="table-th">Remaining Time</th>
                                <th class="table-th">Status</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($subdomains as $sub)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td text-gray-200">
                                        <div class="font-medium">{{ $sub->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">{{ $sub->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="table-td font-mono text-sm text-primary-400">{{ $sub->name }}</td>
                                    <td class="table-td">
                                        <a href="https://{{ $sub->full_domain }}" target="_blank" class="text-gray-400 hover:text-white transition-colors flex items-center gap-1">
                                            {{ $sub->full_domain }}
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        @if($sub->expired_at)
                                            <div class="text-sm {{ $sub->expired_at->isPast() ? 'text-red-400 font-semibold' : 'text-gray-200' }}">
                                                {{ $sub->expired_at->diffForHumans() }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $sub->expired_at->format('d M Y') }}</div>
                                        @else
                                            <span class="text-gray-500 italic text-sm">Lifetime/None</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border 
                                            {{ $sub->status === 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.subdomains.edit', $sub) }}" class="text-sm font-medium text-primary-400 hover:text-primary-300 transition-colors">Edit</a>
                                            <form action="{{ route('admin.subdomains.destroy', $sub) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subdomain? This will remove all associated deployments.');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-400 transition-colors">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="table-td text-center py-12 text-gray-500">No subdomains found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">{{ $subdomains->links() }}</div>
            </div>
        </div>
    </div>
</x-admin-layout>
