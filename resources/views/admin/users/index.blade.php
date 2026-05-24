<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="btn-primary active:scale-[0.98]">
                Register New User
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Client Accounts Directory</h1>
            <p class="text-xs text-neutral-500 font-medium">Moderate system authentication records, access keys, and assign admin role privileges.</p>
        </div>

        @if (session('success'))
            <div class="bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-950/20 border border-red-900/30 text-red-400 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="glass-panel p-4 flex flex-col md:flex-row justify-between items-center gap-4 border-neutral-900">
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-1/2 flex gap-2">
                <input type="hidden" name="subdomain_filter" value="{{ $subdomainFilter ?? '' }}">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search clients by name or email..." class="input-field mt-0 flex-1 py-2 text-xs bg-neutral-950 border border-neutral-900 focus:border-neutral-700">
                <button type="submit" class="btn-primary py-2 px-4 whitespace-nowrap text-xs active:scale-[0.98]">Search</button>
                @if(isset($search) && $search)
                    <a href="{{ route('admin.users.index', ['subdomain_filter' => $subdomainFilter ?? '']) }}" class="btn-secondary py-2 px-4 whitespace-nowrap text-xs active:scale-[0.98]">Clear</a>
                @endif
            </form>

            <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                <a href="{{ route('admin.users.index', ['search' => $search ?? '']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border whitespace-nowrap active:scale-[0.96] transition-all
                    {{ !($subdomainFilter ?? null) ? 'bg-white text-black border-transparent' : 'bg-neutral-950 text-neutral-500 border-neutral-900 hover:border-neutral-800' }}">All</a>
                <a href="{{ route('admin.users.index', ['subdomain_filter' => 'active', 'search' => $search ?? '']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border whitespace-nowrap active:scale-[0.96] transition-all
                    {{ ($subdomainFilter ?? null) === 'active' ? 'bg-white text-black border-transparent' : 'bg-neutral-950 text-neutral-500 border-neutral-900 hover:border-neutral-800' }}">Active Host</a>
                <a href="{{ route('admin.users.index', ['subdomain_filter' => 'inactive', 'search' => $search ?? '']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border whitespace-nowrap active:scale-[0.96] transition-all
                    {{ ($subdomainFilter ?? null) === 'inactive' ? 'bg-white text-black border-transparent' : 'bg-neutral-950 text-neutral-500 border-neutral-900 hover:border-neutral-800' }}">Inactive Host</a>
                <a href="{{ route('admin.users.index', ['subdomain_filter' => 'none', 'search' => $search ?? '']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border whitespace-nowrap active:scale-[0.96] transition-all
                    {{ ($subdomainFilter ?? null) === 'none' ? 'bg-white text-black border-transparent' : 'bg-neutral-950 text-neutral-500 border-neutral-900 hover:border-neutral-800' }}">No Host</a>
            </div>
        </div>

        <div class="glass-panel overflow-hidden border-neutral-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px]">User Profile</th>
                            <th class="table-th text-[10px]">Role Access</th>
                            <th class="table-th text-[10px]">Joined Date</th>
                            <th class="table-th text-[10px] text-center">Verified</th>
                            <th class="table-th text-right text-[10px] pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($users as $user)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-neutral-900 border border-neutral-850 flex items-center justify-center text-[10px] font-bold text-white uppercase">{{ substr($user->name, 0, 1) }}</div>
                                        <div>
                                            <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $user->name }}</div>
                                            <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase tracking-wider rounded-md border 
                                        {{ $user->role === 'Admin' ? 'bg-neutral-900/40 text-neutral-250 border-neutral-800' : 'bg-neutral-950 text-neutral-500 border-neutral-900' }}">
                                        @if($user->role === 'Admin')
                                            <svg class="w-3 h-3 mr-1 text-white inline-block" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        @endif
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="table-td text-neutral-450 font-bold text-xs">
                                    {{ $user->created_at->format('M d, Y') }}
                                    <span class="text-[9px] text-neutral-550 block mt-0.5">{{ $user->created_at->format('H:i') }}</span>
                                </td>
                                <td class="table-td text-center">
                                    @if($user->email_verified_at)
                                        <div class="text-xs font-bold text-neutral-300 flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $user->email_verified_at->format('M d, Y') }}
                                        </div>
                                        <span class="text-[9px] text-neutral-550 block mt-0.5 font-bold">{{ $user->email_verified_at->format('H:i') }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-red-950/20 text-red-400 border border-red-900/10">Not Verified</span>
                                    @endif
                                </td>
                                <td class="table-td text-right pr-8 select-none">
                                    <div class="flex items-center justify-end gap-3.5">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-neutral-500 hover:text-white transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="confirm('Are you sure you want to delete this client account permanently? This deletes all associated hosting deployments, databases, subdomains, and records.');">
                                            @csrf @method('DELETE')
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
                                <td colspan="5" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</x-admin-layout>
