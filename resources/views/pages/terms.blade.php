<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Terms of Service - {{ config('app.name', 'Subly') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        
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
                                <img type="image/png" src="{{ asset('favicon.png') }}" alt="Subly">
                            </div>
                            <span class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                                {{ config('app.name', 'Subly') }}
                            </span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Log in</a>
                            @endauth
                        @endif
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
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Ketentuan Layanan</h1>
                        <p class="text-lg text-gray-400">Pembaruan Terakhir: {{ date('d F Y') }}</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        <p class="lead">Selamat datang di Subly. Dengan menggunakan situs dan layanan kami, Anda setuju untuk mematuhi dan terikat oleh syarat dan ketentuan penggunaan berikut, yang bersama dengan kebijakan privasi kami, mengatur hubungan Subly dengan Anda terkait situs ini.</p>
                        <br>
                        <h2>1. Penerimaan Syarat</h2>
                        <p>Dengan mengakses atau menggunakan layanan kami, Anda mengonfirmasi penerimaan Anda terhadap Syarat dan Ketentuan ini. Jika Anda tidak menyetujui persyaratan ini, Anda dilarang keras menggunakan layanan kami.</p>
                        <br>
                        <h2>2. Ketentuan Layanan</h2>
                        <p>Kami berhak untuk mengubah atau menghentikan, sementara atau secara permanen, layanan (atau bagian mana pun darinya) dengan atau tanpa pemberitahuan. Kami menyediakan subdomain, platform penerapan (deployment), dan akses basis data sebagaimana diuraikan dalam paket pilihan Anda.</p>
                        <br>
                        <h2>3. Akun Pengguna</h2>
                        <p>Anda bertanggung jawab untuk menjaga keamanan akun, kata sandi, dan data file Anda. Anda bertanggung jawab penuh atas seluruh aktivitas yang terjadi di bawah akun tersebut serta tindakan apa pun yang berkaitan dengannya.</p>
                        <br>
                        <h2>4. Batasan Tanggung Jawab</h2>
                        <p>Dalam keadaan apa pun, Subly, beserta direktur, karyawan, mitra, agen, pemasok, atau afiliasinya, tidak akan bertanggung jawab atas kerugian tidak langsung, insidental, khusus, konsekuensial, atau hukuman, termasuk namun tidak terbatas pada, hilangnya keuntungan, data, penggunaan, niat baik, atau kerugian tidak berwujud lainnya yang diakibatkan oleh akses atau penggunaan Anda, maupun ketidakmampuan Anda untuk mengakses atau menggunakan Layanan kami.</p>
                        <br>
                        <h2>5. Hukum yang Berlaku</h2>
                        <p>Syarat-syarat ini diatur dan ditafsirkan sesuai dengan hukum yurisdiksi tempat Subly beroperasi, tanpa memperhatikan ketentuan pertentangan hukumnya.</p>
                    </div>
                </div>

                <!-- ENGLISH CONTENT -->
                <div x-show="lang === 'en'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                    <div class="mb-12 text-center text-balance">
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Terms of Service</h1>
                        <p class="text-lg text-gray-400">Last updated: {{ date('F d, Y') }}</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        <p class="lead">Welcome to Subly. By using our website and services, you agree to comply with and be bound by the following terms and conditions of use, which together with our privacy policy, govern Subly's relationship with you in relation to this website.</p>
                        <br>
                        <h2>1. Acceptance of Terms</h2>
                        <p>By accessing or using our services, you confirm your acceptance of these Terms and Conditions. If you do not agree to these terms, you must strictly abstain from using our services.</p>
                        <br>
                        <h2>2. Service Provision</h2>
                        <p>We reserve the right to modify or discontinue, temporarily or permanently, the service (or any part thereof) with or without notice. We provide subdomains, deployment platforms, and database access as outlined in your selected plan.</p>
                        <br>
                        <h2>3. User Account</h2>
                        <p>You are responsible for maintaining the security of your account, passwords, and files. You are fully responsible for all activities that occur under the account and any other actions taken in connection with it.</p>
                        <br>
                        <h2>4. Limitation of Liability</h2>
                        <p>In no event shall Subly, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of, or inability to access or use the Service.</p>
                        <br>
                        <h2>5. Governing Law</h2>
                        <p>These terms shall be governed by and construed in accordance with the laws of the jurisdiction in which Subly operates, without regard to its conflict of law provisions.</p>
                    </div>
                </div>

            </div>
        </div>

        <x-footer />
    </body>
</html>
