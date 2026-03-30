<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Subly') }} - Managed Hosting</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <link rel="icon" type="image/png" href="{{ asset('favicon-v2.png') }}">
    </head>
    <body class="font-sans antialiased bg-gray-950 text-gray-300 min-h-screen flex flex-col pt-8 sm:pt-0 relative overflow-x-hidden selection:bg-primary-500/30 selection:text-primary-100">
        
        <!-- Ambient Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary-900/20 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-900/20 blur-[120px]"></div>
            <div class="absolute top-[40%] left-[50%] translate-x-[-50%] w-[60%] h-[20%] rounded-full bg-primary-500/5 blur-[100px]"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMiIvPjwvc3ZnPg==')] opacity-20"></div>
        </div>

        <div class="relative z-10 w-full flex flex-col min-h-screen">
            <!-- Header -->
            <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                        <img type="image/png" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                    </div>
                    <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">Subly</span>
                </div>
                
                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 rounded-md bg-white/10 hover:bg-white/20 border border-white/5 transition-all hidden sm:inline-block">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex flex-col items-center justify-center px-6 text-center max-w-6xl mx-auto py-20 pb-32 w-full">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-400 text-xs font-medium mb-8">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    Subly Hosting v1.0
                </div>
                
                <h1 class="text-5xl sm:text-7xl font-bold tracking-tight text-white mb-6 leading-tight">
                    Deploy projects <br class="hidden sm:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-indigo-400">without the hassle.</span>
                </h1>
                
                <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mb-10 leading-relaxed">
                    A managed hosting platform designed for students. Upload your code, and we'll handle the infrastructure, database setup, and deployment automatically.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-medium shadow-[0_0_20px_rgba(94,106,210,0.3)] transition-all flex items-center justify-center gap-2">
                            Go to Dashboard
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="#pricing" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-medium shadow-[0_0_20px_rgba(94,106,210,0.3)] transition-all flex items-center justify-center gap-2">
                                View Hosting Plans
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7"></path></svg>
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-lg bg-gray-800 border border-gray-700 hover:bg-gray-700 text-white font-medium transition-all flex items-center justify-center">
                            Sign In
                        </a>
                    @endauth
                </div>
                
                <!-- Feature Highlights -->
                <div id="features" class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-24 text-left w-full border-t border-gray-800/50 pt-12">
                    <div class="p-1">
                        <div class="w-10 h-10 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center mb-4 text-primary-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <h3 class="text-white font-semibold mb-2">ZIP Uploads</h3>
                        <p class="text-sm text-gray-400">Simply compress your project files into a ZIP and upload. We extract and structure it for you.</p>
                    </div>
                    <div class="p-1">
                        <div class="w-10 h-10 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center mb-4 text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <h3 class="text-white font-semibold mb-2">Auto-Database</h3>
                        <p class="text-sm text-gray-400">Credentials are automatically provisioned and securely presented in your dashboard when requested.</p>
                    </div>
                    <div class="p-1">
                        <div class="w-10 h-10 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center mb-4 text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-white font-semibold mb-2">Secure Subdomains</h3>
                        <p class="text-sm text-gray-400">Projects are isolated onto personalized subdomains so your applications run independently.</p>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div id="pricing" class="w-full mt-32 pt-20 border-t border-gray-800/50">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Choose Your Plan</h2>
                        <p class="text-gray-400">Select the perfect hosting environment for your student project.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                        @forelse($plans as $plan)
                            <div class="relative group">
                                <div class="absolute -inset-0.5 bg-gradient-to-r from-primary-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                                <div class="relative bg-gray-900 border border-gray-800 rounded-2xl p-8 h-full flex flex-col">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-xl font-bold text-white">{{ $plan->name }}</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $plan->type == 'PHP' ? 'bg-blue-500/20 text-blue-400' : ($plan->type == 'NodeJS' ? 'bg-green-500/20 text-green-400' : 'bg-purple-500/20 text-purple-400') }}">
                                            {{ $plan->type }}
                                        </span>
                                    </div>
                                    <div class="flex items-baseline gap-1 mb-6">
                                        <span class="text-3xl font-bold text-primary-400">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                        <span class="text-sm text-gray-500">/ {{ $plan->duration_months }} mo</span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-400 mb-8 flex-1">
                                        {{ $plan->description ?? 'Reliable hosting for your professional student projects.' }}
                                    </p>

                                    <ul class="space-y-4 mb-8 text-sm text-gray-300">
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $plan->max_storage_mb }}MB SSD Storage
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $plan->max_databases }} Databases
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Free Subdomain
                                        </li>
                                        <li class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Free SSL Certificate
                                        </li>
                                    </ul>

                                    <a href="{{ auth()->check() ? route('client.plans.index') : route('register') }}" class="w-full py-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium text-center transition-all">
                                        {{ auth()->check() ? 'Select Plan' : 'Get Started' }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-gray-500 border border-dashed border-gray-800 rounded-2xl">
                                No plans available at the moment.
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>

            <!-- Testimonials Section -->
            @if($feedbacks->count() > 0)
            <section id="testimonials" class="py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Loved by Students</h2>
                        <p class="text-gray-400 text-lg">See what your peers are saying about Subly.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($feedbacks as $feedback)
                            <div class="glass-panel p-8 relative group">
                                <div class="flex text-yellow-500 mb-4">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 {{ $i < $feedback->rating ? 'fill-current' : 'text-gray-600' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-gray-300 italic mb-6">"{{ $feedback->comment }}"</p>
                                <div class="flex items-center gap-4 mt-auto">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-600 to-purple-600 flex items-center justify-center text-sm font-bold text-white uppercase">
                                        {{ substr($feedback->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $feedback->user->name }}</p>
                                        <p class="text-gray-500 text-xs text-primary-400">Verified Subly Client</p>
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
