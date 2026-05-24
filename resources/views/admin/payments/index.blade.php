<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Billing Transactions</h1>
            <p class="text-xs text-neutral-500 font-medium">Verify customer payment invoices, check QRIS settlement totals, and process pending codes.</p>
        </div>

        <div class="glass-panel overflow-hidden border-neutral-900 flex flex-col">
            <div class="p-5 border-b border-neutral-900/60 bg-neutral-950/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    All Payments
                </h3>
            </div>

            <div class="overflow-x-auto flex-1 relative group/scroll">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px]">Date</th>
                            <th class="table-th text-[10px]">Client</th>
                            <th class="table-th text-[10px]">Plan</th>
                            <th class="table-th text-[10px]">Amount</th>
                            <th class="table-th text-[10px]">Code</th>
                            <th class="table-th text-[10px]">Status</th>
                            <th class="table-th text-[10px]">Transaction ID</th>
                            <th class="table-th text-right text-[10px] pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($payments as $payment)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td text-neutral-455 text-xs font-semibold">
                                    {{ $payment->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="table-td text-neutral-200 transition-colors group-hover:text-white">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-full bg-neutral-900 border border-neutral-850 text-neutral-350 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($payment->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold">{{ $payment->user->name ?? 'Deleted User' }}</span>
                                    </div>
                                </td>
                                <td class="table-td">
                                    <div class="flex flex-col">
                                        <span class="text-white text-xs font-bold">{{ $payment->plan->name ?? 'N/A' }}</span>
                                        @if($payment->subdomain)
                                            <span class="text-[9px] text-neutral-500 font-mono mt-0.5">{{ $payment->subdomain->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="table-td font-mono text-xs text-neutral-350">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="table-td font-mono text-white font-bold text-xs">
                                    {{ $payment->unique_code ?? '-' }}
                                </td>
                                <td class="table-td">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase tracking-wider rounded-md border 
                                        {{ $payment->status === 'success' ? 'bg-neutral-900/40 text-neutral-300 border-neutral-850' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-white text-black border-transparent' : '' }}
                                        {{ in_array($payment->status, ['failed', 'expired', 'cancel']) ? 'bg-red-950/20 text-red-400 border-red-900/10' : '' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td class="table-td">
                                    <div class="flex items-center gap-2">
                                        <span class="text-neutral-500 font-mono text-[11px] font-semibold">{{ $payment->transaction_id ?? '-' }}</span>
                                        @if($payment->transaction_id)
                                            <button onclick="copyToClipboard('{{ $payment->transaction_id }}', this)" class="text-neutral-600 hover:text-white p-1 rounded hover:bg-neutral-900 transition-colors" title="Copy Transaction ID">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="table-td text-right pr-8">
                                    @if($payment->status === 'pending')
                                        <form action="{{ route('admin.payments.confirm', $payment) }}" method="POST" onsubmit="confirm('Verify this client unique QRIS bank deposit and confirm payment?')">
                                            @csrf
                                            <button type="submit" class="btn-primary py-1 px-3 text-xs active:scale-[0.96]">
                                                Confirm
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-neutral-600 text-xs italic font-semibold select-none">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">
                                    No transaction records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($payments->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">
                    {{ $payments->links() }}
                </div>
            @endif
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
                    btn.classList.add('text-white');
                    btn.classList.remove('text-neutral-600');
                    
                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                        btn.classList.remove('text-white');
                        btn.classList.add('text-neutral-600');
                    }, 2000);
                }
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</x-admin-layout>
