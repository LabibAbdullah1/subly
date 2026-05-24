<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            {{ __('Database Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-100 italic">Database Credentials</h3>
                    <p class="text-sm text-gray-400">Manage client database access details.</p>
                </div>
                <a href="{{ route('admin.databases.create') }}" class="btn-primary w-full sm:w-auto text-center">
                    + Assign New Database
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3 shadow-lg" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="table-th text-[11px]">Client</th>
                                <th class="table-th text-[11px]">Subdomain</th>
                                <th class="table-th text-[11px]">DB Name</th>
                                <th class="table-th text-[11px]">DB User</th>
                                <th class="table-th text-right text-[11px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($databases as $db)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td">
                                        <div class="font-medium text-gray-200">{{ $db->subdomain?->user?->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $db->subdomain?->user?->email ?? '' }}</div>
                                    </td>
                                    <td class="table-td truncate max-w-[150px]">
                                        <span class="text-primary-400">{{ $db->subdomain?->full_domain ?? 'Deleted Subdomain' }}</span>
                                    </td>
                                    <td class="table-td font-mono text-xs text-gray-300">
                                        {{ $db->db_name }}
                                    </td>
                                    <td class="table-td font-mono text-xs text-gray-300">
                                        {{ $db->db_user }}
                                    </td>
                                    <td class="table-td text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.databases.edit', $db) }}" class="text-gray-400 hover:text-white transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.databases.destroy', $db) }}" method="POST" class="inline" onsubmit="return confirm('Delete these credentials?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="table-td text-center py-12 text-gray-500 italic">
                                        No database credentials assigned yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($databases->hasPages())
                    <div class="p-4 border-t border-gray-800 bg-gray-900/40">
                        {{ $databases->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
