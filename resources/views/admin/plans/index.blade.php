<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Hosting Plans') }}
            </h2>
            <a href="{{ route('admin.plans.create') }}" class="btn-primary">
                Create New Plan
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
                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="table-th text-center">Name</th>
                                <th class="table-th text-center">Type</th>
                                <th class="table-th text-center">Price/Mo</th>
                                <th class="table-th text-center">Status</th>
                                <th class="table-th text-center">DBs</th>
                                <th class="table-th text-center">Storage</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($plans as $plan)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td font-medium text-gray-200 text-center">{{ $plan->name }}</td>
                                    <td class="table-td">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                            {{ $plan->type === 'PHP' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : '' }}
                                            {{ $plan->type === 'NodeJS' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : '' }}
                                            {{ $plan->type === 'Fullstack' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : '' }}">
                                            {{ $plan->type }}
                                        </span>
                                    </td>
                                    <td class="table-td text-center">
                                        @if($plan->is_active)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-400 border border-red-500/20">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="table-td font-medium text-gray-200">Rp {{ number_format($plan->price, 0, ',', '.') }}</td>
                                    <td class="table-td text-gray-400 text-center">
                                        {{ $plan->max_databases > 0 ? $plan->max_databases : 'None' }}
                                    </td>
                                    <td class="table-td text-gray-400 text-center">{{ $plan->max_storage_mb }} MB</td>
                                    <td class="table-td flex justify-end gap-3 items-center">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-gray-400 hover:text-white transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="table-td text-center py-12 text-gray-500">
                                        No plans created yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">{{ $plans->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
