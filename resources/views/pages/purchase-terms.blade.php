<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Purchase Terms & Refund Policy - {{ config('app.name', 'Subly') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon-v2.png') }}">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:500,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-950 text-gray-100 selection:bg-primary-500/30 selection:text-primary-200">
        
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-900/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-primary-900/10 rounded-full blur-[120px]"></div>
        </div>

        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-gray-950/80 backdrop-blur-md border-b border-gray-800 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                                <img type="image/png" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                            </div>
                            <span class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                                {{ config('app.name', 'Subly') }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="pt-32 pb-20 relative z-10" x-data="{ lang: 'id' }">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Language Toggle -->
                <div class="flex justify-center mb-8">
                    <div class="bg-gray-900/60 p-1 rounded-xl inline-flex border border-gray-800 backdrop-blur-md">
                        <button @click="lang = 'id'" class="px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300" :class="lang === 'id' ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'">Bahasa Indonesia</button>
                        <button @click="lang = 'en'" class="px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-300" :class="lang === 'en' ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50'">English</button>
                    </div>
                </div>

                <!-- INDONESIAN CONTENT -->
                <div x-show="lang === 'id'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                    <div class="mb-12 text-center text-balance">
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Syarat Pembelian & Dana Kembali</h1>
                        <p class="text-lg text-gray-400">Harap baca dengan saksama sebelum bertransaksi.</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        
                        <div class="bg-red-500/10 border border-red-500/50 rounded-lg p-6 mb-8 not-prose">
                            <div class="flex gap-4">
                                <svg class="h-6 w-6 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h3 class="text-red-400 font-bold mb-1">Kebijakan Ketat Tanpa Pengembalian Dana</h3>
                                    <p class="text-red-200/80 text-sm leading-relaxed">Apabila layanan atau akun Anda ditangguhkan akibat pelanggaran Ketentuan Layanan—termasuk, yang utamanya, tindakan mengunggah file berbahaya, skrip ilegal, atau malware—<strong class="text-white">TIDAK ADA PENGEMBALIAN DANA (NO REFUNDS)</strong> yang akan diberikan dalam kondisi apa pun untuk sisa periode masa berlangganan Anda.</p>
                                </div>
                            </div>
                        </div>

                        <h2>1. Transaksi Pembelian</h2>
                        <p>Subly menyediakan paket berbasis langganan bulanan maupun tahunan. Semua paket ditagih di muka selama durasi yang dipilih saat proses pembayaran menggunakan gerbang pembayaran (Payment Gateway) resmi kami. Dengan melakukan pembelian, Anda menyetujui biaya otomatis yang tidak dapat dikembalikan tersebut sepanjang periode aktif.</p>
                        <br>
                        <h2>2. Kelayakan Pengembalian Dana (Refund)</h2>
                        <p>Kami bangga dapat menyediakan platform yang stabil. Pada umumnya, pengembalian dana tidak kami layani. Akan tetapi, kami mungkin mengeluarkan pengembalian dana penuh atau sebagian dalam kasus pengecualian berikut:</p>
                        <br>
                        <ul class="list-decimal list-inside ml-4">
                            <li>Apabila sistem kami sepenuhnya gagal menyediakan server/layanan Anda dalam waktu 48 jam sejak pembayaran pertama berhasil divalidasi.</li>
                            <li>Terdapat kesalahan penagihan teknis yang mengakibatkan biaya ganda pada tagihan yang sama (bukti sah dari pihak bank wajib dilampirkan).</li>
                        </ul>
                        <br>
                        <h2>3. Kewajiban Klien dan Risiko Keamanan</h2>
                        <p>Secara ketat, menjadi kewajiban klien untuk mengamankan aplikasi yang mereka sebarkan (deploy) di Subly. Jika klien—baik secara sengaja maupun akibat kelalaian—mengunggah file yang membahayakan node bersama kami, mendistribusikan malware, atau terlibat dalam kegiatan melanggar hukum, sistem peringatan otomatis kami akan langsung memutus instans tersebut serta menangguhkan akun terkait.</p>
                        <p>Dalam kasus khusus ini:</p>
                        <br>
                        <ol class="list-decimal list-inside ml-4">
                            <li>Akun pelapor akan diblokir secara permanen.</li>
                            <li>Semua data dan isi database klien akan dihapus total secara paksa guna melindungi keamanan klien lain pada peladen yang sama.</li>
                            <li><strong>Sama sekali tidak ada dana yang dikembalikan, baik itu bersifat pro-rata maupun bentuk kompensasi lain.</strong></li>
                        </ol>
                        <br>
                        <h2>4. Sengketa & Chargeback</h2>
                        <p>Setiap pelaporan sengketa (dispute) pembayaran atau *chargeback* yang diajukan langsung kepada bank atau penyedia kartu kredit Anda tanpa lebih dulu menghubungi Tim Support kami, akan berakibat pada penangguhan langsung seluruh layanan aktif beserta penutupan kredensial akun secara permanen.</p>
                        
                        <p class="text-sm text-gray-500 mt-12 pt-8 border-t border-gray-800">Dengan membeli paket dari Subly, Anda telah memberikan persetujuan digital untuk tunduk sepenuhnya di bawah Syarat Pembelian dan Pengembalian Dana ini.</p>
                    </div>
                </div>

                <!-- ENGLISH CONTENT -->
                <div x-show="lang === 'en'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                    <div class="mb-12 text-center text-balance">
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Purchase Terms & Refunds</h1>
                        <p class="text-lg text-gray-400">Please read carefully before transacting.</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        
                        <div class="bg-red-500/10 border border-red-500/50 rounded-lg p-6 mb-8 not-prose">
                            <div class="flex gap-4">
                                <svg class="h-6 w-6 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h3 class="text-red-400 font-bold mb-1">Strict No-Refund Policy for TOS Violations</h3>
                                    <p class="text-red-200/80 text-sm leading-relaxed">In the event your account or server is suspended due to violations of our Terms of Service or Application Rules—specifically including, but not limited to, the uploading of malicious, illegal, or dangerous files—<strong class="text-white">NO REFUNDS</strong> will be provided under any circumstances for the remaining period of your subscription.</p>
                                </div>
                            </div>
                        </div>

                        <h2>1. General Purchases</h2>
                        <p>Subly provides subscription-based plans. All plans are billed upfront for the duration indicated during the checkout process (e.g., monthly). Payment gateways used include official providers. By making a purchase, you agree to automatic, non-refundable charges for the selected duration.</p>
                        <br>
                        <h2>2. Refund Eligibility</h2>
                        <p>We pride ourselves on providing a stable and reliable platform. Refunds are generally not accepted. However, we may issue a full or partial refund in the following exceptional cases:</p>
                        <br>
                        <ul class="list-decimal list-inside ml-4">
                            <li>If our system fails to provision your service entirely within 48 hours of successful payment validation.</li>
                            <li>If there are billing errors resulting in double charges (proof from the payment provider is strictly required).</li>
                        </ul>
                        <br>
                        <h2>3. Client Liability and Security Disasters</h2>
                        <p>It is strictly the client's responsibility to secure their applications deployed on Subly. If a client mistakenly or deliberately uploads files that compromise the security of our shared nodes, distribute malware, or otherwise engage in illegal activities, our automated security sweeps will immediately terminate the deployed instance and suspend the user account.</p>
                        <p>In cases of termination due to client-induced security breaches or rule violations:</p>
                        <br>
                        <ol class="list-decimal list-inside ml-4">
                            <li>Your account will be permanently banned.</li>
                            <li>All associated data and databases will be forcefully wiped to protect other users on the node.</li>
                            <li><strong>Absolutely no refunds, prorated or otherwise, will be issued for the suspended service.</strong></li>
                        </ol>
                        <br>
                        <h2>4. Chargebacks and Disputes</h2>
                        <p>Any dispute or chargeback initiated via your bank or credit card company without prior contact with our Support Team will result in the immediate suspension of all active services and permanent termination of your account credentials.</p>
                        <br>

                        <p class="text-sm text-gray-500 mt-12 pt-8 border-t border-gray-800">By purchasing any plan from Subly, you digitally sign and consent to strictly follow these Purchase and Refund terms.</p>
                    </div>
                </div>

            </div>
        </div>

        <x-footer />
    </body>
</html>
