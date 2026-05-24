<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center w-full gap-4">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Renew Subdomain:') }} <span class="text-primary-400">{{ $subdomain->full_domain }}</span>
            </h2>
            <a href="{{ route('client.index') }}" class="btn-secondary w-full sm:w-auto text-center">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-lg flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="glass-panel overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-500 to-indigo-500"></div>
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-white mb-2">Renewal Invoice</h3>
                            <p class="text-gray-400 text-sm">You are extending the active period for the following environment.</p>
                        </div>
                        <div class="text-left md:text-right bg-primary-500/5 border border-primary-500/20 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Current Expiration</p>
                            <p class="text-lg font-medium text-white">
                                {{ $subdomain->expired_at ? $subdomain->expired_at->format('d M Y') : 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-400 mt-1">
                                {{ $subdomain->expired_at && $subdomain->expired_at->isPast() ? 'Expired' : 'Active' }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-900/50 rounded-xl border border-gray-800 p-6 mb-8">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-800 mb-4">
                            <div>
                                <h4 class="text-lg font-medium text-gray-100">Package: {{ $plan->name }}</h4>
                                <p class="text-sm text-gray-400 mt-1">Extends hosting for <span class="text-white font-medium">{{ $plan->duration_months }} months</span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-white">Rp{{ number_format($plan->price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Target Environment</span>
                            <span class="font-mono text-primary-400">{{ $subdomain->full_domain }}</span>
                        </div>
                    </div>

                    <form action="{{ route('client.checkout.process', ['plan' => $plan->id, 'renew' => $subdomain->id]) }}" method="POST">
                        @csrf
                        
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Have a Promo Code?</label>
                            <div class="flex gap-3">
                                <div class="relative flex-1 group/input">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500 group-focus-within/input:text-primary-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="voucher_code" placeholder="Enter voucher code" 
                                        class="w-full pl-10 bg-gray-900/50 border border-gray-800 rounded-lg py-2.5 text-sm focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all text-gray-100 placeholder-gray-600">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Discounts will be calculated on the next screen.</p>
                        </div>

                        <div class="border-t border-gray-800/60 pt-6">
                            <button type="submit" class="w-full btn-primary py-3.5 text-lg font-medium shadow-[0_0_20px_rgba(94,106,210,0.3)] hover:scale-[1.01] transition-transform">
                                Proceed to Payment
                            </button>
                            <p class="text-center text-xs text-gray-500 mt-4">By continuing, your active duration will be extended strictly according to the selected package.</p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
