<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('User Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                Register New User
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
            @if (session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-lg flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-th">User Profile</th>
                                <th class="table-th">Role Access</th>
                                <th class="table-th">Joined Date</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($users as $user)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-gray-700 to-gray-600 flex items-center justify-center text-xs font-bold text-white uppercase shadow-lg">{{ substr($user->name, 0, 1) }}</div>
                                            <div>
                                                <div class="font-medium text-gray-200">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border 
                                            {{ $user->role === 'Admin' ? 'bg-primary-500/10 text-primary-400 border-primary-500/20 shadow-[0_0_10px_rgba(94,106,210,0.2)]' : 'bg-gray-800 text-gray-400 border-gray-700' }}">
                                            @if($user->role === 'Admin')
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            @endif
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="table-td text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                        <span class="text-xs text-gray-600 block">{{ $user->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="table-td flex justify-end gap-3 items-center pt-5">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-400 hover:text-white transition-colors">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This will also remove their deployments.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="table-td text-center py-12 text-gray-500">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-admin-layout>
