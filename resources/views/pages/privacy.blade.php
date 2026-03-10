<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Privacy Policy - {{ config('app.name', 'Subly') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        
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
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center transform group-hover:scale-105 transition-all shadow-[0_0_15px_rgba(94,106,210,0.4)]">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 9h19M2.5 15h19M12 2c3 0 6 4.477 6 10s-3 10-6 10-6-4.477-6-10 3-10 6-10z"></path>
                                </svg>
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
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Kebijakan Privasi</h1>
                        <p class="text-lg text-gray-400">Bagaimana kami mengelola dan mengamankan data Anda.</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        
                        <p class="lead">Privasi Anda sangat penting bagi Subly. Kami berkomitmen dalam menjaga kerahasiaan sekaligus integritas data dari setiap klien yang menggunakan layanan hosting dan database kami.</p>

                        <h2>1. Pengumpulan Informasi</h2>
                        <p>Kami mengumpulkan jenis-jenis informasi berikut guna melayani Anda dengan sistem terotomatisasi:</p>
                        <ul>
                            <li><strong>Informasi Personal:</strong> Seperti nama lengkap, dan alamat surel (email) saat proses registrasi.</li>
                            <li><strong>Data Transaksi:</strong> Rincian paket tagihan. Harap dicatat, kami <strong>tidak</strong> menyimpan nomor kartu kredit/debit secara mandiri karena semuanya diproses secara aman oleh Penyedia Gerbang Pembayaran kami.</li>
                            <li><strong>Data Layanan & Log:</strong> Rincian log penerapan (deployment), spesifikasi resource, tipe OS, dan lalu-lintas jaringan secara garis demografi umum untuk menunjang performa teknis.</li>
                        </ul>

                        <h2>2. Penggunaan Informasi</h2>
                        <p>Kami menggunakan data yang telah dikumpulkan tidak lain hanya untuk keperluan pengembangan serta fungsionalitas aplikasi kami:</p>
                        <ul>
                            <li>Penyediaan, pengelolaan, dan pengoperasian arsitektur *cloud* dan *hosting* untuk Anda.</li>
                            <li>Pemrosesan pesanan pembelian langganan dan pengiriman notifikasi terkait akun (seperti peringatan masa aktif).</li>
                            <li>Pemantauan potensi insiden keamanan (seperti pola serangan DDoS atau pencurian paket data infrastruktur server).</li>
                        </ul>

                        <h2>3. Berbagi dan Keamanan Data</h2>
                        <p>Subly <strong>tidak pernah menjual, menukar, maupun menyewakan</strong> informasi identitas pribadi (PII) audiens kami kepada pihak ketiga untuk kepentingan pemasaran.</p>
                        <p>Perihal keamanan, kami telah mengaplikasikan protokol perlindungan Secure Socket Layer (SSL), penyandian (hash) basis data menggunakan kriptografi mutakhir bcrypt, serta filter web server ekstensif demi menjamin privasi transmisi antara peramban (browser) milik Anda dan sistem *cloud* kami.</p>

                        <h2>4. File Kode Milik Klien (Client Data)</h2>
                        <p>Kode proyek, repositori basis data, serta seluruh sumber aset yang Anda unggah ke peladen kami melalui platform *deployment* atau FTP sepenuhnya berada di bawah kendali kontrol eksklusif Anda. Subly maupun tim administrasinya tidak berhak menelusuri atau membaca modifikasi kode privat Anda kecuali dimintakan mediasi penanganan khusus melalui Layanan Chat Administrasi (Live Support) oleh klien itu sendiri.</p>

                    </div>
                </div>

                <!-- ENGLISH CONTENT -->
                <div x-show="lang === 'en'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                    <div class="mb-12 text-center text-balance">
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Privacy Policy</h1>
                        <p class="text-lg text-gray-400">How we manage and secure your data.</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        
                        <p class="lead">Your privacy is extremely important to Subly. We are committed to maintaining the confidentiality and data integrity of every client who relies on our hosting and database services.</p>

                        <h2>1. Information Collection</h2>
                        <p>We collect the following types of information to serve you via our automated systems:</p>
                        <ul>
                            <li><strong>Personal Information:</strong> Such as full name, and email address during the registration sequence.</li>
                            <li><strong>Transactional Data:</strong> Billing plan details. Please be advised that we <strong>do not</strong> store your credit/debit card numbers natively, as all of this is securely processed by our third-party Payment Gateway Provider.</li>
                            <li><strong>Service & Log Data:</strong> Deployment architectural logs, resource specifications, OS types, and general demographic network traffic to properly regulate technical performance ratios.</li>
                        </ul>

                        <h2>2. Use of Information</h2>
                        <p>We utilize the collected data strictly for internal development logic and platform functionalities:</p>
                        <ul>
                            <li>Provisioning, maintaining, and operating the cloud hosting architecture assigned to you.</li>
                            <li>Processing subscription checkout orders and dispatching vital account-related push notifications (such as expiration alerts).</li>
                            <li>Monitoring and intercepting potential security incidents (such as patterned DDoS attacks or infrastructure packet theft).</li>
                        </ul>

                        <h2>3. Data Sharing and Security Protocol</h2>
                        <p>Subly <strong>never sells, trades, or unethically rents</strong> personally identifiable information (PII) of our audiences to external third parties for marketing endeavors.</p>
                        <p>Regarding cyber-security, we have actively applied Secure Socket Layer (SSL) protection protocols, database hashing via cutting-edge bcrypt cryptography methodologies, and extensive web-server firewalls to guarantee absolute transmission privacy between your active browser window and our cloud mainframe.</p>

                        <h2>4. Client Asset Modules (Client Data)</h2>
                        <p>Project source codes, distinct database repositories, and all raw asset resources uploaded into our server environments through the internal deployment platform uniquely reside under your exclusive domain control. Subly and its administrative delegates possess no inherent right to peer into or review your private code modifications whatsoever unless explicitly requested to operate a specialized intervention via the Live Chat Administration by the respective client.</p>

                    </div>
                </div>

            </div>
        </div>

        <x-footer />
    </body>
</html>
