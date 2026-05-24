<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
            </svg>
            {{ __('Database Management') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-4 sm:px-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Database Credentials</h1>
                <p class="text-xs text-neutral-500 font-medium mt-1">Manage database connections and access details for clients.</p>
            </div>
            <a href="{{ route('admin.databases.create') }}" class="btn-primary w-full sm:w-auto text-center active:scale-[0.98]">
                Assign New Database
            </a>
        </div>

        @if (session('success'))
            <div class="mx-4 sm:mx-0 bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        <div class="mx-4 sm:mx-0 glass-panel overflow-hidden border-neutral-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px]">Client</th>
                            <th class="table-th text-[10px]">Subdomain</th>
                            <th class="table-th text-[10px]">Database Name</th>
                            <th class="table-th text-[10px]">Username</th>
                            <th class="table-th text-right text-[10px] pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($databases as $db)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td">
                                    <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $db->subdomain?->user?->name ?? 'N/A' }}</div>
                                    <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">{{ $db->subdomain?->user?->email ?? '' }}</div>
                                </td>
                                <td class="table-td truncate max-w-[200px]">
                                    <span class="text-xs font-bold text-white font-mono">{{ $db->subdomain?->full_domain ?? 'Deleted Subdomain' }}</span>
                                </td>
                                <td class="table-td font-mono text-xs text-neutral-350">
                                    {{ $db->db_name }}
                                </td>
                                <td class="table-td font-mono text-xs text-neutral-350">
                                    {{ $db->db_user }}
                                </td>
                                <td class="table-td text-right pr-8">
                                    <div class="flex items-center justify-end gap-3.5">
                                        <a href="{{ route('admin.databases.edit', $db) }}" class="text-neutral-500 hover:text-white transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.databases.destroy', $db) }}" method="POST" class="inline" onsubmit="confirm('Delete these database credentials permanently?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-neutral-600 hover:text-red-400 transition-colors cursor-pointer" title="Delete">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">
                                    No database credentials assigned yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($databases->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">
                    {{ $databases->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
