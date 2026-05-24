<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Hosting Plans') }}
            </h2>
            <a href="{{ route('admin.plans.create') }}" class="btn-primary active:scale-[0.98]">
                Create New Plan
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Hosting Service Packages</h1>
            <p class="text-xs text-neutral-500 font-medium">Create and adjust pricing models, cPanel technology stacks, storage limits, and database counts.</p>
        </div>

        @if (session('success'))
            <div class="bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        <div class="glass-panel overflow-hidden border-neutral-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px]">Name</th>
                            <th class="table-th text-[10px]">Type Stack</th>
                            <th class="table-th text-[10px] text-center">Months</th>
                            <th class="table-th text-[10px] text-center">Price</th>
                            <th class="table-th text-[10px] text-center">Status</th>
                            <th class="table-th text-[10px] text-center">DB Limit</th>
                            <th class="table-th text-[10px] text-center">Storage Size</th>
                            <th class="table-th text-right text-[10px] pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($plans as $plan)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $plan->name }}</td>
                                <td class="table-td">
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-neutral-900 border border-neutral-850 text-neutral-300">
                                        {{ $plan->type }}
                                    </span>
                                </td>
                                <td class="table-td text-center text-neutral-450 font-bold text-xs">{{ $plan->duration_months }}</td>
                                <td class="table-td font-mono text-xs text-neutral-300 text-center">Rp {{ number_format($plan->price, 0, ',', '.') }}</td>
                                <td class="table-td text-center">
                                    @if($plan->is_active)
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-neutral-900 border border-neutral-850 text-neutral-250">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-red-950/20 text-red-400 border border-red-900/10">Inactive</span>
                                    @endif
                                </td>
                                <td class="table-td text-neutral-450 font-bold text-xs text-center">
                                    {{ $plan->max_databases > 0 ? $plan->max_databases : 'None' }}
                                </td>
                                <td class="table-td text-neutral-450 font-bold text-xs text-center">{{ $plan->max_storage_mb }} MB</td>
                                <td class="table-td text-right pr-8">
                                    <div class="flex items-center justify-end gap-3.5 select-none">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-neutral-500 hover:text-white transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="confirm('Are you sure you want to delete this subscription plan?');">
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
                                <td colspan="8" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">
                                    No plans created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($plans->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">{{ $plans->links() }}</div>
            @endif
        </div>
    </div>
</x-admin-layout>
