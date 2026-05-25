<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-5.625-12h14.25c.621 0 1.125.504 1.125 1.125v13.5c0 .621-.504 1.125-1.125 1.125H3.375c-.621 0-1.125-.504-1.125-1.125V4.875c0-.621.504-1.125 1.125-1.125z" />
            </svg>
            Checkout Pembayaran
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 select-none">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl p-6 sm:p-10 flex flex-col items-center text-center shadow-2xl relative overflow-hidden">
                <div class="absolute -right-24 -top-24 w-52 h-52 bg-white/2 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="mb-6 relative z-10">
                    <div class="w-14 h-14 bg-neutral-950 border border-neutral-900 rounded-2xl flex items-center justify-center mb-4 mx-auto shadow-xl">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white mb-2 tracking-tight">Selesaikan Pembayaran</h1>
                    <p class="text-neutral-400 text-xs sm:text-sm font-medium leading-relaxed max-w-sm mx-auto">Silakan scan kode QRIS di bawah ini melalui GoPay, OVO, Dana, LinkAja, atau m-Banking Anda.</p>
                </div>

                <!-- Invoice Details Box -->
                <div class="w-full bg-black/60 rounded-2xl border border-neutral-900 p-6 mb-8 text-left relative z-10">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-neutral-500 text-[10px] font-bold uppercase tracking-widest">Item Paket</span>
                        <span class="text-white font-bold text-xs sm:text-sm">{{ $payment->plan->name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-neutral-500 text-[10px] font-bold uppercase tracking-widest">ID Transaksi</span>
                        <span class="text-neutral-300 font-mono text-xs">{{ $payment->transaction_id }}</span>
                    </div>
                    <div class="h-px bg-neutral-900/60 my-4"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-neutral-500 block mb-1 text-[10px] font-bold uppercase tracking-widest">Total Bayar</span>
                            <span class="text-[9px] text-neutral-400 uppercase tracking-widest font-extrabold">Termasuk Kode Unik: +{{ $payment->unique_code }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm sm:text-lg font-mono font-extrabold text-white">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Static QRIS Scanner Module -->
                @php
                    $qrisImage = \App\Models\Setting::get('qris_image_path', 'images/qris_static.png');
                    
                    // Normalize backslashes to forward slashes and trim slashes/spaces
                    $normalizedPath = trim(str_replace('\\', '/', $qrisImage), '/ ');
                    
                    // Handle cases where 'storage/' is already prepended in database setting
                    if (strpos($normalizedPath, 'storage/') === 0) {
                        $normalizedPath = substr($normalizedPath, 8);
                    }
                    
                    // Self-healing path checker
                    if (file_exists(public_path($normalizedPath))) {
                        $qrisUrl = asset($normalizedPath);
                    } elseif (file_exists(public_path('storage/' . $normalizedPath))) {
                        $qrisUrl = asset('storage/' . $normalizedPath);
                    } elseif (strpos($normalizedPath, 'images/') === 0 || strpos($normalizedPath, 'uploads/') === 0) {
                        $qrisUrl = asset($normalizedPath);
                    } else {
                        $qrisUrl = asset('storage/' . $normalizedPath);
                    }

                    // Debug logging to help identify why the image is not loading
                    try {
                        $debugInfo = [
                            'timestamp' => date('Y-m-d H:i:s'),
                            'raw_setting' => $qrisImage,
                            'normalized_path' => $normalizedPath,
                            'resolved_url' => $qrisUrl,
                            'public_path' => public_path(),
                            'request_host' => request()->getSchemeAndHttpHost(),
                            'request_url' => request()->fullUrl(),
                            'file_exists_in_public' => file_exists(public_path($normalizedPath)) ? 'YES' : 'NO',
                            'file_exists_in_storage' => file_exists(public_path('storage/' . $normalizedPath)) ? 'YES' : 'NO',
                        ];
                        file_put_contents(public_path('debug_qris.txt'), print_r($debugInfo, true));
                    } catch (\Exception $e) {
                        // ignore failures
                    }
                @endphp
                
                <div class="relative group mb-8 w-full max-w-[280px] sm:max-w-[320px] bg-neutral-950 p-2.5 rounded-2xl border border-neutral-900 shadow-2xl overflow-hidden flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/2 to-transparent opacity-40"></div>
                    <img src="{{ $qrisUrl }}" alt="QRIS Payment" class="w-full h-auto object-contain relative z-10 transition-all duration-300 mx-auto select-none rounded-xl shadow-md">
                </div>

                <!-- Instructions Alert Box -->
                <div class="w-full space-y-4 relative z-10">
                    <div class="p-4 bg-neutral-950 border border-neutral-900 rounded-xl text-neutral-350 text-xs sm:text-sm flex items-start gap-3 text-left font-medium leading-relaxed">
                        <svg class="w-5 h-5 text-neutral-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376C1.83 15.002 2.067 13.785 3 13.125L9.932 8.5c1.24-.827 2.896-.827 4.136 0L21 13.125c.933.66 1.17 1.877.432 2.626L14.5 20.25c-1.24.827-2.896.827-4.136 0L3 15.75c-.933-.66-1.17-1.877-.432-2.626z" />
                        </svg>
                        <p>Pastikan nominal yang Anda transfer <strong class="text-white">sama persis</strong> hingga 3 digit terakhir untuk mempercepat proses verifikasi otomatis oleh sistem admin.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button onclick="confirmViaChat()" class="btn-primary h-12 w-full flex items-center justify-center gap-2 text-xs uppercase tracking-wider font-extrabold active:scale-[0.98]">
                            <svg class="w-4 h-4 text-black shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20,2H4C2.9,2,2,2.9,2,4v18l4-4h14c1.1,0,2-0.9,2-2V4C22,2.9,21.1,2,20,2z" />
                            </svg>
                            Konfirmasi via Chat
                        </button>
                        <a href="{{ route('client.checkout.cancel', $payment) }}" class="btn-danger h-12 w-full flex items-center justify-center gap-2 text-xs uppercase tracking-wider font-extrabold active:scale-[0.98]" onclick="return confirm('Apakah Anda yakin ingin membatalkan pembayaran ini?')">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batalkan Bayar
                        </a>
                    </div>
                    
                    <div class="flex flex-col items-center gap-1.5 pt-2">
                        <a href="{{ route('client.index') }}" class="text-[10px] font-bold text-neutral-500 hover:text-white uppercase tracking-widest transition-colors underline">Ke Dashboard</a>
                        <p class="text-[10px] text-neutral-500 italic max-w-sm mx-auto font-semibold leading-relaxed mt-2">Verifikasi manual diselesaikan dalam 5-10 menit pada jam kerja. Halaman ini akan otomatis diperbarui secara realtime setelah admin menyetujui transaksi Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Realtime status polling for payment confirmation
        const paymentId = "{{ $payment->id }}";
        const checkStatusUrl = "{{ route('client.checkout.status', $payment) }}";
        const successUrl = "{{ route('client.checkout.success') }}";

        let pollingInterval = setInterval(async () => {
            try {
                const response = await fetch(checkStatusUrl);
                const data = await response.json();

                if (data.status === 'success') {
                    clearInterval(pollingInterval);
                    if (typeof window.showToast === 'function') {
                        window.showToast('Pembayaran berhasil diverifikasi!');
                    }
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
        }, 5000);

        function confirmViaChat() {
            const message = "Halo Admin, saya sudah membayar untuk {{ $payment->subdomain ? 'perpanjangan subdomain ' . $payment->subdomain->name : 'pembelian plan ' . $payment->plan->name }}. \n\nTransaction ID: {{ $payment->transaction_id }}\nTotal: Rp {{ number_format($payment->amount, 0, ',', '.') }}\n\nBerikut bukti bayarnya:";
            
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
