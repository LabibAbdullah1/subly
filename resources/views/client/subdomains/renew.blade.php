<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center w-full gap-4">
            <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                {{ __('Renew Subdomain') }}
            </h2>
            <a href="{{ route('client.index') }}" class="btn-secondary h-10 text-xs px-4 flex items-center justify-center font-bold uppercase tracking-wider active:scale-[0.98]">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 select-none">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if (session('error'))
                <div class="bg-neutral-950 border border-red-900/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xl" role="alert">
                    <div class="p-1 rounded-lg bg-red-950/20 border border-red-900/30 text-red-400 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <p class="text-xs font-semibold tracking-wide">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl overflow-hidden relative shadow-2xl">
                <div class="absolute -right-20 -top-20 w-48 h-48 bg-white/2 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-base font-bold text-white mb-1.5 tracking-tight uppercase tracking-wider">Renewal Invoice</h3>
                            <p class="text-neutral-450 text-xs font-medium">You are extending the active hosting period for the following environment.</p>
                        </div>
                        <div class="text-left md:text-right bg-neutral-950 border border-neutral-900 rounded-xl p-4 shrink-0 shadow-lg">
                            <p class="text-[9px] text-neutral-500 uppercase tracking-widest font-bold mb-1">Current Expiration</p>
                            <p class="text-xs sm:text-sm font-bold text-white font-mono">
                                {{ $subdomain->expired_at ? $subdomain->expired_at->format('d M Y') : 'N/A' }}
                            </p>
                            <span class="inline-block mt-2 px-2 py-0.5 text-[8px] font-bold uppercase tracking-wider rounded-md border {{ $subdomain->expired_at && $subdomain->expired_at->isPast() ? 'bg-red-950/20 border-red-900/30 text-red-400' : 'bg-neutral-900 border-neutral-850 text-white' }}">
                                {{ $subdomain->expired_at && $subdomain->expired_at->isPast() ? 'Expired' : 'Active' }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-black/60 rounded-xl border border-neutral-900 p-6 mb-8">
                        <div class="flex justify-between items-center pb-4 border-b border-neutral-900/60 mb-4">
                            <div>
                                <h4 class="text-sm font-bold text-white">Package: {{ $plan->name }}</h4>
                                <p class="text-xs text-neutral-450 mt-1 font-medium">Extends hosting for <span class="text-white font-bold">{{ $plan->duration_months }} months</span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-base sm:text-lg font-mono font-extrabold text-white">Rp{{ number_format($plan->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs font-medium">
                            <span class="text-neutral-500">Target Environment</span>
                            <span class="font-mono text-white">{{ $subdomain->full_domain }}</span>
                        </div>
                    </div>

                    <form action="{{ route('client.checkout.process', ['plan' => $plan->id, 'renew' => $subdomain->id]) }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2 pl-0.5">Have a Promo Code?</label>
                            <div class="relative group/input">
                                <input type="text" name="voucher_code" placeholder="Enter voucher code" 
                                    class="mt-1 block w-full rounded-xl bg-black border border-neutral-850 text-neutral-200 px-3.5 py-2.5 placeholder-neutral-600 focus:border-neutral-500 focus:ring-1 focus:ring-neutral-500 text-xs sm:text-sm transition-all duration-200 outline-none">
                            </div>
                            <p class="text-[9px] text-neutral-500 mt-2 font-semibold">*Discounts and vouchers will be calculated on the checkout invoice screen.</p>
                        </div>

                        <div class="border-t border-neutral-900/60 pt-6">
                            <button type="submit" class="w-full btn-primary h-12 uppercase tracking-wider font-extrabold text-xs active:scale-[0.98] cursor-pointer">
                                Proceed to Payment
                            </button>
                            <p class="text-center text-[9px] text-neutral-500 mt-4 font-semibold">By continuing, your active subdomain duration will be extended strictly according to the selected plan.</p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
