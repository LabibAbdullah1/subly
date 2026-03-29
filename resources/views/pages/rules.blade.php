<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Application Rules - {{ config('app.name', 'Subly') }}</title>
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
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Peraturan Aplikasi</h1>
                        <p class="text-lg text-gray-400">Panduan Ketat Penggunaan Aplikasi</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        
                        <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-6 mb-8 not-prose">
                            <div class="flex gap-4">
                                <svg class="h-6 w-6 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h3 class="text-yellow-400 font-bold mb-1">Kebijakan Tanpa Toleransi (Zero Tolerance)</h3>
                                    <p class="text-yellow-200/80 text-sm leading-relaxed">Pelanggaran terhadap peraturan aplikasi mana pun akan mengakibatkan penangguhan akun secara institusional serta larangan permanen tanpa pemberitahuan sebelumnya.</p>
                                </div>
                            </div>
                        </div>

                        <h2>1. Konten Terlarang</h2>
                        <br>
                        <p>Anda diizinkan menggunakan sistem kami, namun dilarang keras meng-hosting, menransmisikan, atau menyediakan konten yang berupa:</p>
                        <ul class="list-disc list-inside ml-4">
                            <li>Malware, spyware, phishing, shell, atau jenis kode berbahaya apa pun.</li>
                            <li>Situs phishing atau skema penipuan.</li>
                            <li>Situs ilegal menurut hukum lokal dan internasional yang berlaku.</li>
                            <li>Konten dewasa, pornografi, atau materi eksplisit tanpa verifikasi usia dan kepatuhan yang memadai.</li>
                            <li>Materi yang melanggar hak cipta digital (Piracy).</li>
                        </ul>
                        <br>
                        <h2>2. Penyalahgunaan Sumber Daya (Resource Abuse)</h2>
                        <p>Anda setuju untuk tidak secara sengaja menyalahgunakan sumber daya bersama pada server. Hal ini termasuk, namun tidak terbatas pada:</p>
                        <ul class="list-disc list-inside ml-4 wrap-break-word">
                            <li>Menjalankan penambang mata uang kripto (Cryptocurrency, misalnya penambangan server jarak jauh).</li>
                            <li>Alat serangan DDoS yang disengaja atau pemindai (scanner) jaringan publik.</li>
                            <li>Proses CPU atau IO berkelanjutan yang secara sengaja ditujukan untuk menurunkan rasio performa beban pengguna lain yang ada di node tersebut.</li>
                        </ul>
                        <br>

                        <h2>3. Panduan Keamanan</h2>
                        <p>Klien diharapkan mandiri menjaga keamanan infrastruktur aplikasi mereka sendiri:</p>
                        <ul class="list-disc list-inside ml-4">
                            <li>Selalu perbarui dependensi paket dan kerangka kerja (framework) yang terhubung ke platform kami untuk menambal celah keamanan.</li>
                            <li>Jangan menyimpan kata sandi teks biasa atau kunci API rahasia yang tidak dienkripsi di dalam basis data Anda.</li>
                            <li>Segera laporkan kerentanan keamanan yang ditemukan pada infrastruktur kami melalui Live Chat Support, alih-alih mengeksploitasinya secara sepihak.</li>
                        </ul>
                    </div>
                </div>

                <!-- ENGLISH CONTENT -->
                <div x-show="lang === 'en'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                    <div class="mb-12 text-center text-balance">
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white mb-4">Application Rules</h1>
                        <p class="text-lg text-gray-400">Strict Guidelines for Application Usage</p>
                    </div>

                    <div class="glass-panel p-8 md:p-12 prose prose-invert prose-primary max-w-none prose-headings:font-outfit">
                        
                        <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-6 mb-8 not-prose">
                            <div class="flex gap-4">
                                <svg class="h-6 w-6 text-yellow-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h3 class="text-yellow-400 font-bold mb-1">Zero Tolerance Policy</h3>
                                    <p class="text-yellow-200/80 text-sm leading-relaxed">Violation of any application rule will result in immediate institutional suspension and a permanent ban of your account without prior notice.</p>
                                </div>
                            </div>
                        </div>

                        <h2>1. Prohibited Content</h2>
                        <p>You may use our systems, but you may not host, transmit, or otherwise make available any content that is:</p>
                        <ul class="list-disc list-inside ml-4">
                            <li>Malware, spyware, phishing, shells, or malicious code of any kind.</li>
                            <li>Phishing sites or fraudulent schemes.</li>
                            <li>Illegal under applicable local and international laws.</li>
                            <li>Adult content, pornography, or explicit material without proper age verification and compliance.</li>
                            <li>Copyright infringing material (Piracy).</li>
                        </ul>
                        <br>

                        <h2>2. Resource Abuse</h2>
                        <p>You agree not to deliberately abuse the shared resources of the server. This includes, but is not limited to:</p>
                        <ul class="list-disc list-inside ml-4 wrap-break-word">
                            <li>Running cryptocurrency miners (e.g., remote server mining).</li>
                            <li>Deliberate DDoS attack tools or public network scanners.</li>
                            <li>Sustained CPU or IO processes that intentionally degrade the performance load ratio of other users on the node.</li>
                        </ul>
                        <br>

                        <h2>3. Security Guidelines</h2>
                        <p>Clients are expected to independently maintain the security infrastructure of their applications:</p>
                        <ul class="list-disc list-inside ml-4">
                            <li>Always update package dependencies and frameworks connected to our platform to patch security holes.</li>
                            <li>Do not store plaintext passwords or sensitive API keys unencrypted inside your database.</li>
                            <li>Immediately report any discovered security weaknesses in our infrastructure to us via our Live Chat Support, rather than unilaterally exploiting them.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <x-footer />
    </body>
</html>
