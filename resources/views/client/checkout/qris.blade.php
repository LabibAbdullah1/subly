<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('QRIS Payment') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-panel p-6 sm:p-10 flex flex-col items-center text-center">
                <div class="mb-6">
                    <div class="w-16 h-16 bg-primary-500/20 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <svg class="w-8 h-8 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Selesaikan Pembayaran</h1>
                    <p class="text-gray-400">Silakan scan kode QRIS di bawah ini melalui aplikasi pembayaran favorit Anda.</p>
                </div>

                <!-- Invoice Details -->
                <div class="w-full bg-gray-950/50 rounded-2xl border border-gray-800/50 p-6 mb-8 text-left">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-500">Item</span>
                        <span class="text-gray-200 font-medium">{{ $payment->plan->name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-500">Transaction ID</span>
                        <span class="text-gray-400 text-sm font-mono">{{ $payment->transaction_id }}</span>
                    </div>
                    <div class="h-px bg-gray-800/50 my-4"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-gray-500 block mb-1">Total Bayar</span>
                            <span class="text-xs text-primary-400 font-medium uppercase tracking-wider">Termasuk Kode Unik: +{{ $payment->unique_code }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl sm:text-3xl font-bold text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- QRIS Image -->
                @php
                    $qrisImage = \App\Models\Setting::get('qris_image_path', 'images/qris_static.png');
                    $qrisUrl = ($qrisImage && (strpos($qrisImage, 'images/') === 0 || strpos($qrisImage, 'uploads/') === 0)) 
                        ? asset($qrisImage) 
                        : asset('storage/' . $qrisImage);
                @endphp
                <div class="relative group mb-8">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-purple-500 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-white p-4 rounded-xl shadow-2xl">
                        <img src="{{ $qrisUrl }}" alt="QRIS Payment" class="w-64 h-64 sm:w-80 sm:h-80 object-contain mx-auto">
                    </div>
                </div>

                <!-- Instructions & Confirmation -->
                <div class="w-full space-y-4">
                    <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl text-amber-200 text-sm flex items-start gap-3 text-left">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>Pastikan nominal yang Anda transfer <strong>sama persis</strong> hingga 3 digit terakhir untuk mempercepat proses verifikasi otomatis oleh admin.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button onclick="confirmViaChat()" class="flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-primary-500/20">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20,2H4C2.9,2,2,2.9,2,4v18l4-4h14c1.1,0,2-0.9,2-2V4C22,2.9,21.1,2,20,2z" />
                            </svg>
                            Konfirmasi via Chat
                        </button>
                        <a href="{{ route('client.checkout.cancel', $payment) }}" class="w-full flex items-center justify-center gap-2 bg-red-600/10 hover:bg-red-600/20 text-red-500 font-semibold py-3 px-6 rounded-xl transition-all border border-red-500/20 hover:border-red-500/40" onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batalkan Pembayaran
                        </a>
                    </div>
                    
                    <a href="{{ route('client.index') }}" class="block text-sm text-gray-500 hover:text-gray-400 transition-colors mt-2 underline">Ke Dashboard</a>
                    
                    <p class="text-xs text-gray-500 mt-4 italic">Verifikasi manual biasanya dilakukan dalam 5-10 menit pada jam kerja. Halaman ini akan otomatis diperbarui setelah admin melakukan verifikasi.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Polling for payment status
        const paymentId = "{{ $payment->id }}";
        const checkStatusUrl = "{{ route('client.checkout.status', $payment) }}";
        const successUrl = "{{ route('client.checkout.success') }}";

        let pollingInterval = setInterval(async () => {
            try {
                const response = await fetch(checkStatusUrl);
                const data = await response.json();

                if (data.status === 'success') {
                    clearInterval(pollingInterval);
                    window.showToast('Pembayaran berhasil diverifikasi!');
                    setTimeout(() => {
                        window.location.href = successUrl;
                    }, 1500);
                } else if (data.status === 'failed') {
                    clearInterval(pollingInterval);
                    window.location.href = "{{ route('client.plans.index') }}";
                }
            } catch (error) {
                console.error('Error checking payment status:', error);
            }
        }, 5000); // Check every 5 seconds

        function confirmViaChat() {
            const message = "Halo Admin, saya sudah membayar untuk {{ $payment->subdomain ? 'perpanjangan subdomain ' . $payment->subdomain->name : 'pembelian plan ' . $payment->plan->name }}. \n\nTransaction ID: {{ $payment->transaction_id }}\nTotal: Rp {{ number_format($payment->amount, 0, ',', '.') }}\n\nBerikut bukti bayarnya:";
            
            // Redirect to chat with pre-filled message
            // We'll use a session or local storage to pass the message if the chat component doesn't support query params
            // For now, let's try to send it via AJAX if possible, or just redirect to chat index
            
            // Actually, let's use the ChatController@store via fetch and then redirect
            fetch('{{ route("client.chat.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            }).then(response => {
                window.location.href = '{{ route("client.chat.index") }}';
            }).catch(error => {
                console.error('Error sending message:', error);
                window.location.href = '{{ route("client.chat.index") }}';
            });
        }
    </script>
    @endpush
</x-app-layout>
