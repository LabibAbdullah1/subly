<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Subly') }} - Managed Hosting Premium</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @include('layouts.assets')

        <link rel="icon" type="image/png" href="{{ asset('favicon-v2.png') }}">
    </head>
    <body class="font-sans antialiased bg-black text-neutral-350 min-h-screen flex flex-col pt-8 sm:pt-0 relative overflow-x-hidden selection:bg-neutral-200 selection:text-black">
        
        <!-- Ambient Background & Dot Grid -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-black select-none">
            <!-- Fine SVG Dot Matrix Pattern with Eased Masking to prevent banding -->
            <div class="absolute inset-0 bg-dot-grid [mask-image:radial-gradient(ellipse_120%_120%_at_50%_0%,#000_0%,rgba(0,0,0,0.95)_40%,rgba(0,0,0,0.6)_70%,rgba(0,0,0,0.15)_90%,transparent_100%)] opacity-90"></div>
            
            <!-- Soft Ambient Radial Glows -->
            <div class="absolute top-[-15%] left-[-15%] w-[60%] h-[60%] rounded-full bg-primary-500/18 blur-[140px]"></div>
            <div class="absolute bottom-[-15%] right-[-15%] w-[60%] h-[60%] rounded-full bg-primary-600/12 blur-[140px]"></div>
            <div class="absolute top-[35%] left-[50%] -translate-x-1/2 w-[80%] h-[30%] rounded-full bg-primary-500/9 blur-[130px]"></div>

            <!-- High-Fidelity Dithering Noise Overlay -->
            <div class="absolute inset-0 bg-noise opacity-[0.015] mix-blend-overlay"></div>
        </div>

        <div class="relative z-10 w-full flex flex-col min-h-screen">
            <!-- Header -->
            <header class="w-full max-w-7xl mx-auto px-6 py-8 flex justify-between items-center relative z-10 select-none">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-black border border-neutral-900 shadow-md">
                        <img type="image/png" src="{{ asset('favicon-v2.png') }}" alt="Subly" class="w-5.5 h-5.5 object-contain">
                    </div>
                    <span class="text-xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-neutral-100 to-neutral-500 font-heading">Subly</span>
                </div>
                
                @if (Route::has('login'))
                    <nav class="flex items-center gap-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-xs font-bold uppercase tracking-wider text-neutral-450 hover:text-white transition-colors font-heading">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-neutral-450 hover:text-white transition-colors font-heading">
                                Masuk
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-secondary py-2 px-4.5 text-xs font-bold tracking-wider hidden sm:inline-flex">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex flex-col items-center justify-center px-6 text-center max-w-6xl mx-auto py-20 pb-32 w-full relative z-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary-500/5 border border-primary-500/10 text-primary-400 text-[10px] font-bold uppercase tracking-wider mb-8 select-none font-heading shadow-[0_0_15px_rgba(94,106,210,0.05)]">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    Subly Hosting v2.2
                </div>
                
                <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight text-white mb-6 leading-tight select-none font-heading">
                    Deploy proyek Anda <br class="hidden sm:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-400 to-primary-600">tanpa ribet.</span>
                </h1>
                
                <p class="text-xs sm:text-sm text-neutral-450 max-w-lg mb-12 leading-relaxed font-semibold">
                    Platform managed hosting premium yang dirancang khusus untuk mahasiswa dan pelajar. Hubungkan langsung GitHub Anda atau unggah file ZIP, dan kami akan mengurus otomatisasi cPanel, keamanan database MySQL, serta pemantauan disk secara instan.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary w-full sm:w-auto px-8 gap-2.5">
                            <span>Buka Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="#pricing" class="btn-primary w-full sm:w-auto px-8 gap-2.5">
                                <span>Lihat Paket Hosting</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="btn-secondary w-full sm:w-auto px-8">
                            Masuk
                        </a>
                    @endauth
                </div>
                
                <!-- Feature Highlights -->
                <div id="features" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-28 text-left w-full border-t border-neutral-900 pt-16 select-none">
                    <!-- Feature 1: Unggah ZIP (Theme Standard) -->
                    <div class="glass-panel glass-panel-glow p-6 sm:p-8 feature-card">
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center mb-5 text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-wide mb-2 text-sm uppercase font-heading">Unggah ZIP</h3>
                        <p class="text-xs sm:text-xs text-neutral-450 font-semibold leading-relaxed">Cukup kompres file proyek Anda ke format .zip dan unggah. Kami akan mengekstrak dan menatanya untuk Anda secara otomatis.</p>
                    </div>
                    <!-- Feature 2: Monitoring Penyimpanan Real-Time (Theme Standard) -->
                    <div class="glass-panel glass-panel-glow p-6 sm:p-8 feature-card">
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center mb-5 text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-wide mb-2 text-sm uppercase font-heading">Penyimpanan Real-Time</h3>
                        <p class="text-xs sm:text-xs text-neutral-450 font-semibold leading-relaxed">Pantau secara transparan dan akurat kapasitas penyimpanan direktori file proyek Anda beserta besaran database MySQL secara terpadu langsung dari server cPanel.</p>
                    </div>
                    <!-- Feature 3: Subdomain Aman (Theme Standard) -->
                    <div class="glass-panel glass-panel-glow p-6 sm:p-8 feature-card">
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center mb-5 text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-wide mb-2 text-sm uppercase font-heading">Subdomain Aman</h3>
                        <p class="text-xs sm:text-xs text-neutral-450 font-semibold leading-relaxed">Setiap proyek diisolasi ke dalam subdomain pribadi agar aplikasi Anda dapat berjalan secara independen dan aman.</p>
                    </div>
                    <!-- Feature 4: SSL / HTTPS Otomatis (Theme Standard) -->
                    <div class="glass-panel glass-panel-glow p-6 sm:p-8 feature-card">
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center mb-5 text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-wide mb-2 text-sm uppercase font-heading">SSL / HTTPS Otomatis</h3>
                        <p class="text-xs sm:text-xs text-neutral-450 font-semibold leading-relaxed">Setiap subdomain dilengkapi dengan sertifikat SSL gratis (Let's Encrypt) yang aktif secara otomatis untuk menjaga keamanan data pengguna.</p>
                    </div>
                    <!-- Feature 5: Integrasi GitHub (SPECIAL HIGHLIGHT: Glowing Badge & Emerald Accents) -->
                    <div class="glass-panel glass-panel-glow p-6 sm:p-8 feature-card relative overflow-hidden group">
                        <span class="absolute top-3 right-3 px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[8px] font-bold uppercase tracking-wider select-none font-heading animate-pulse">Terbaru</span>
                        
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center mb-5 text-emerald-400 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-wide mb-2 text-sm uppercase font-heading">Integrasi GitHub</h3>
                        <p class="text-xs sm:text-xs text-neutral-450 font-semibold leading-relaxed">Hubungkan repositori GitHub Anda secara langsung. Verifikasi akses instan, pilih target branch dinamis, dan lakukan pembaruan kode (Git Pull) 1-klik yang andal.</p>
                    </div>
                    <!-- Feature 6: Chat Dengan Admin (SPECIAL HIGHLIGHT: Brighter Purple Glow & Distinct Border Highlight on Hover) -->
                    <div class="glass-panel glass-panel-glow p-6 sm:p-8 feature-card-special relative overflow-hidden group">
                        <!-- Glowing Badge in the Corner to stand out -->
                        <span class="absolute top-3 right-3 px-2 py-0.5 rounded bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[8px] font-bold uppercase tracking-wider select-none">Live Support</span>
                        
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center mb-5 text-purple-400 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        </div>
                        <h3 class="text-white font-bold tracking-wide mb-2 text-sm uppercase font-heading">Chat Dengan Admin</h3>
                        <p class="text-xs sm:text-xs text-neutral-450 font-semibold leading-relaxed">Konsultasikan kendala teknis atau tanyakan progres deploy secara langsung dengan admin melalui fitur Live Chat interaktif di portal Anda.</p>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div id="pricing" class="w-full mt-32 pt-20 border-t border-neutral-900">
                    <div class="text-center mb-16 select-none">
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-tight font-heading">Pilih Lingkungan Anda</h2>
                        <p class="text-xs sm:text-sm text-neutral-500 font-semibold uppercase tracking-wider">Pilih sumber daya hosting yang paling sesuai untuk kebutuhan deploy proyek Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                        @forelse($plans as $plan)
                            <div class="relative group">
                                <!-- Glowing Border Accent on Hover -->
                                <div class="absolute -inset-[1px] bg-gradient-to-r from-primary-500/30 to-purple-600/30 rounded-2xl blur opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
                                <div class="relative bg-neutral-950/60 backdrop-blur-xl border border-neutral-900 group-hover:border-neutral-800 rounded-2xl p-8 h-full flex flex-col transition-all duration-300 shadow-[0_12px_40px_rgba(0,0,0,0.6)]">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-base font-bold text-white tracking-tight font-heading uppercase">{{ $plan->name }}</h4>
                                        <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-wider font-heading {{ $plan->type == 'PHP' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : ($plan->type == 'NodeJS' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                                            {{ $plan->type }}
                                        </span>
                                    </div>
                                    <div class="flex items-baseline gap-1.5 mb-6 border-b border-neutral-900/60 pb-5">
                                        <span class="text-2xl font-black text-white tracking-tight font-heading">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-neutral-500 font-bold uppercase tracking-wider">/ {{ $plan->duration_months }} bln</span>
                                    </div>
                                    
                                    <p class="text-xs sm:text-xs text-neutral-450 font-semibold mb-6 flex-1 leading-relaxed">
                                        {{ $plan->description ?? 'Layanan hosting andal untuk mendukung proyek profesional Anda.' }}
                                    </p>

                                    <!-- Disk Storage Meter visual details (Stripe Invoice aesthetic) -->
                                    <div class="mt-2 mb-6">
                                        <div class="flex justify-between text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-1.5">
                                            <span>Alokasi Penyimpanan SSD</span>
                                            <span>{{ $plan->max_storage_mb }}MB SSD</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-neutral-900 rounded-full overflow-hidden border border-neutral-850">
                                            <div class="h-full bg-gradient-to-r from-primary-500 to-indigo-500 rounded-full" style="width: {{ min(100, max(15, ($plan->max_storage_mb / 2048) * 100)) }}%"></div>
                                        </div>
                                    </div>

                                    <ul class="space-y-3.5 mb-8 text-xs text-neutral-350 font-semibold border-t border-neutral-900/60 pt-6">
                                        <li class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            <span>{{ $plan->max_storage_mb }}MB SSD Disk</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            <span>{{ $plan->max_databases }} Database Produksi</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Slot Subdomain Terisolasi</span>
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            <span>Perlindungan SSL Otomatis</span>
                                        </li>
                                    </ul>

                                    <a href="{{ auth()->check() ? route('client.plans.index') : route('register') }}" class="btn-secondary w-full text-center py-3 text-xs font-bold uppercase tracking-wider">
                                        {{ auth()->check() ? 'Pilih Paket' : 'Mulai Sekarang' }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-neutral-500 border border-dashed border-neutral-900 rounded-2xl font-semibold text-xs uppercase tracking-wider">
                                Belum ada paket yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>

            <!-- Testimonials Section -->
            @if($feedbacks->count() > 0)
            <section id="testimonials" class="py-28 relative overflow-hidden w-full border-t border-neutral-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="text-center mb-16 select-none">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 tracking-tight font-heading">Dicintai oleh Mahasiswa & Pelajar</h2>
                        <p class="text-xs sm:text-sm text-neutral-500 font-semibold uppercase tracking-wider">Lihat apa yang rekan-rekan Anda katakan tentang Subly.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($feedbacks as $feedback)
                            <div class="glass-panel glass-panel-glow p-6 sm:p-8 relative group flex flex-col hover:-translate-y-1 hover:shadow-[0_15px_40px_rgba(0,0,0,0.8)] transition-all duration-300">
                                <div class="flex text-yellow-500 mb-4 select-none">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i < $feedback->rating ? 'fill-current' : 'text-neutral-800' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-neutral-300 italic mb-8 text-xs sm:text-xs font-semibold leading-relaxed">"{{ $feedback->comment }}"</p>
                                <div class="flex items-center gap-4 mt-auto select-none pt-4 border-t border-neutral-900/60">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-r from-primary-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white uppercase shadow-md shadow-primary-500/10">
                                        {{ substr($feedback->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-xs tracking-wide font-heading">{{ $feedback->user->name }}</p>
                                        <p class="text-neutral-500 text-[9px] font-bold uppercase tracking-wider font-heading">Klien Terverifikasi</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            <x-footer />
        </div>
    </body>
</html>
