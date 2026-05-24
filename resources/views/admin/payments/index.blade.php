<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="glass-panel overflow-hidden flex flex-col hover-lift">
                <div class="p-6 border-b border-gray-800/50 bg-gray-900/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        All Payments
                    </h3>
                </div>

                <div class="overflow-x-auto flex-1 relative group/scroll">
                    <!-- Scroll Indicator (Mobile only) -->
                    <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-gray-950/50 to-transparent pointer-events-none opacity-0 group-hover/scroll:opacity-100 sm:hidden transition-opacity z-10"></div>
                    
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="table-th">Date</th>
                                <th class="table-th">Client</th>
                                <th class="table-th">Plan</th>
                                <th class="table-th">Amount</th>
                                <th class="table-th">Code</th>
                                <th class="table-th">Status</th>
                                <th class="table-th">Transaction ID</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($payments as $payment)
                                <tr class="group hover:bg-primary-500/5 transition-all duration-300">
                                    <td class="table-td text-gray-400">
                                        {{ $payment->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="table-td text-gray-200 transition-colors group-hover:text-white">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-primary-500/20 text-primary-400 flex items-center justify-center text-xs font-bold border border-primary-500/30">
                                                {{ substr($payment->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span>{{ $payment->user->name ?? 'Deleted User' }}</span>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex flex-col">
                                            <span class="text-primary-400 font-medium">{{ $payment->plan->name ?? 'N/A' }}</span>
                                            @if($payment->subdomain)
                                                <span class="text-xs text-gray-500">{{ $payment->subdomain->name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-td font-mono">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="table-td font-mono text-primary-400 font-bold">
                                        {{ $payment->unique_code ?? '-' }}
                                    </td>
                                    <td class="table-td">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold uppercase tracking-wider rounded border 
                                            {{ $payment->status === 'success' ? 'bg-green-500/10 text-green-400 border-green-500/20' : '' }}
                                            {{ $payment->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : '' }}
                                            {{ in_array($payment->status, ['failed', 'expired', 'cancel']) ? 'bg-red-500/10 text-red-400 border-red-500/20' : '' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-500 font-mono text-xs">{{ $payment->transaction_id ?? '-' }}</span>
                                            @if($payment->transaction_id)
                                                <button onclick="copyToClipboard('{{ $payment->transaction_id }}', this)" class="text-gray-600 hover:text-primary-400 transition-colors" title="Copy Transaction ID">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-td text-right">
                                        @if($payment->status === 'pending')
                                            <form action="{{ route('admin.payments.confirm', $payment) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran ini?')">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white text-xs font-bold py-1.5 px-3 rounded transition-colors uppercase tracking-wider">
                                                    Confirm
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-600 text-xs italic">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="table-td text-center py-12 text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <svg class="w-12 h-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            <p>No transaction history found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($payments->hasPages())
                    <div class="p-4 border-t border-gray-800/50 bg-gray-900/30">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        function copyToClipboard(text, btn) {
            const copy = () => {
                if (navigator.clipboard) {
                    return navigator.clipboard.writeText(text);
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-9999px";
                    textArea.style.top = "0";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        textArea.remove();
                        return Promise.resolve();
                    } catch (err) {
                        textArea.remove();
                        return Promise.reject(err);
                    }
                }
            };

            copy().then(() => {
                const copyIcon = btn.querySelector('.copy-icon');
                const checkIcon = btn.querySelector('.check-icon');
                
                if (typeof window.showToast === 'function') {
                    window.showToast('Transaction ID Copied!');
                }
                
                if (copyIcon && checkIcon) {
                    copyIcon.classList.add('hidden');
                    checkIcon.classList.remove('hidden');
                    btn.classList.add('text-green-400');
                    btn.classList.remove('text-gray-600');
                    
                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                        btn.classList.remove('text-green-400');
                        btn.classList.add('text-gray-600');
                    }, 2000);
                }
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</x-admin-layout>
