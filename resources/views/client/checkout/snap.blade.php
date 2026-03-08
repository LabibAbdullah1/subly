<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Finalize Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-10 text-center max-w-2xl mx-auto">
                <div class="mb-8">
                    <div class="w-16 h-16 bg-primary-500/20 text-primary-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-100 mb-2">Complete Your Payment</h3>
                    <div class="text-gray-400 space-y-1">
                        <p>Purchasing: <span class="text-gray-200 font-semibold">{{ $plan->name }}</span></p>
                        @if($discount > 0)
                            <p class="text-xs">Original Price: <span class="line-through">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span></p>
                            <p class="text-xs text-green-400">Discount: -Rp {{ number_format($discount, 0, ',', '.') }}</p>
                        @endif
                        <p class="text-lg">Total Amount: <span class="text-primary-400 font-bold">Rp {{ number_format($grossAmount, 0, ',', '.') }}</span></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <button id="pay-button" class="w-full btn-primary py-4 text-lg shadow-[0_0_20px_rgba(94,106,210,0.5)]">
                        Pay with Midtrans
                    </button>
                    <a href="{{ route('client.plans.index') }}" class="block text-sm text-gray-500 hover:text-gray-300 transition-colors">
                        Cancel and Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @if(config('midtrans.is_production'))
            <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @else
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @endif
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){
                        window.location.href = "{{ route('client.checkout.success') }}";
                    },
                    onPending: function(result){
                        alert("Wating for payment...");
                    },
                    onError: function(result){
                        alert("Payment failed!");
                    }
                });
            };
        </script>
    @endpush
</x-app-layout>
