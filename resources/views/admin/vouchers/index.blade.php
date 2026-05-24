<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Discount Vouchers') }}
            </h2>
            <a href="{{ route('admin.vouchers.create') }}" class="btn-primary">
                Create New Voucher
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
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="table-th">Code</th>
                                <th class="table-th text-center">Discount</th>
                                <th class="table-th text-center">Used</th>
                                <th class="table-th text-center">Limit</th>
                                <th class="table-th text-center">Expires At</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($vouchers as $voucher)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td font-mono text-primary-400 tracking-wider font-semibold">{{ $voucher->code }}</td>
                                    <td class="table-td text-center text-gray-200">
                                        @if($voucher->type == 'percent')
                                            {{ $voucher->reward_amount }}%
                                        @else
                                            Rp {{ number_format($voucher->reward_amount, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="table-td text-center">
                                        <span class="px-2 py-0.5 rounded text-xs font-bold {{ $voucher->successful_payments_count > 0 ? 'bg-primary-500/20 text-primary-400' : 'bg-gray-800 text-gray-500' }}">
                                            {{ $voucher->successful_payments_count }}
                                        </span>
                                    </td>
                                    <td class="table-td text-center text-gray-400">
                                        {{ $voucher->usage_limit ? $voucher->usage_limit . ' left' : 'Unlimited' }}
                                    </td>
                                    <td class="table-td text-center text-gray-400">
                                        @if($voucher->expires_at)
                                            <span class="{{ $voucher->expires_at->isPast() ? 'text-red-400 font-semibold' : '' }}">
                                                {{ $voucher->expires_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            Never
                                        @endif
                                    </td>
                                    <td class="table-td flex justify-end gap-3 items-center">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="text-gray-400 hover:text-white transition-colors">Edit</a>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this voucher?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="table-td text-center py-12 text-gray-500">No vouchers created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">{{ $vouchers->links() }}</div>
            </div>
        </div>
    </div>
</x-admin-layout>
