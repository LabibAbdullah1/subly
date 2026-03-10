<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            {{ __('Client Portal Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3 animate-fade-in" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-lg flex items-start gap-3 shadow-lg" role="alert">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Subscription Widget -->
            @forelse($unusedPayments as $payment)
                @php $plan = $payment->plan; @endphp
                <div class="glass-panel overflow-hidden relative group hover-lift mb-6">
                    <div class="absolute -right-10 -top-10 w-48 h-48 bg-primary-500/10 rounded-full blur-3xl group-hover:bg-primary-500/20 transition-all duration-700 pointer-events-none"></div>
                    <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-xl font-semibold text-gray-100">{{ $plan->name }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20 shadow-[0_0_10px_rgba(34,197,94,0.1)]">Active</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-3">
                                <p class="text-sm text-gray-400 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Expires: <span class="text-gray-200">{{ $payment->created_at->addMonths($plan->duration_months)->format('d M Y') }}</span>
                                </p>
                                <p class="text-sm text-gray-400 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Time left: <span class="text-gray-200">{{ max(0, (int)now()->startOfDay()->diffInDays($payment->created_at->addMonths($plan->duration_months)->startOfDay(), false)) }} days</span>
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if($loop->first)
                                <a href="{{ route('client.plans.index') }}" class="btn-primary shadow-[0_0_15px_rgba(94,106,210,0.3)]">
                                    Purchase Another Plan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-panel overflow-hidden relative group hover-lift mb-6">
                    <div class="absolute -right-10 -top-10 w-48 h-48 bg-primary-500/10 rounded-full blur-3xl group-hover:bg-primary-500/20 transition-all duration-700 pointer-events-none"></div>
                    <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-xl font-semibold text-gray-100">No Active Plan</h3>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('client.plans.index') }}" class="btn-primary shadow-[0_0_15px_rgba(94,106,210,0.3)]">
                                View Hosting Plans
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse

            <!-- 2. Start Project & Hosted Environments -->
            @if($subdomains->count() > 0 && $available_slots > $subdomains->count())
                <div class="glass-panel p-6 mb-6 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 border border-primary-500/30 shadow-[0_0_20px_rgba(94,106,210,0.1)]">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-gray-100 mb-1 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            Claim Your Next Subdomain
                        </h3>
                        <p class="text-gray-400 text-sm">You have <span class="text-white font-bold">{{ $available_slots - $subdomains->count() }}</span> unused subdomain slot(s) available.</p>
                    </div>
                    <div class="relative z-10 w-full md:w-auto mt-2 md:mt-0">
                        <form action="{{ route('client.subdomains.store') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
                            @csrf
                            <div class="relative group flex items-center bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 w-full sm:w-64 focus-within:ring-1 focus-within:ring-primary-500/50 focus-within:border-primary-500 transition-all">
                                <input type="text" name="name" class="bg-transparent border-none p-0 focus:ring-0 text-gray-100 font-mono text-sm w-full" placeholder="project-name" required pattern="[a-zA-Z0-9\-_]+">
                                <span class="text-gray-500 font-mono text-sm pl-2 ml-2 border-l border-gray-800">{{ config('app.subdomain_suffix') }}</span>
                            </div>
                            <button type="submit" class="btn-primary py-2.5 px-6 shadow-[0_0_15px_rgba(94,106,210,0.3)] whitespace-nowrap w-full sm:w-auto hover:scale-[1.02] transition-transform">Claim</button>
                        </form>
                    </div>
                </div>
            @endif

            @if($subdomains->count() > 0)
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        Hosted Environments
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subdomains as $sub)
                        <div class="glass-panel p-6 hover-lift flex flex-col relative overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex items-center gap-3 mb-4 relative z-10">
                                <div class="p-2 rounded-lg {{ $sub->status == 'active' ? 'bg-green-500/10 text-green-400 shadow-[0_0_10px_rgba(34,197,94,0.1)]' : 'bg-red-500/10 text-red-400' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-100 truncate flex-1">
                                    <a href="https://{{ $sub->full_domain }}" target="_blank" class="hover:text-primary-400 transition-colors">{{ $sub->full_domain }}</a>
                                </h3>
                            </div>
                            
                            <div class="space-y-3 mb-6 flex-1 relative z-10">
                                <div class="flex justify-between items-center text-sm bg-gray-900/50 p-2 rounded-lg border border-gray-800/50">
                                    <span class="text-gray-400 font-medium">Status</span>
                                    <span class="{{ $sub->status == 'active' ? 'text-green-400 shadow-[0_0_10px_rgba(34,197,94,0.1)] bg-green-500/10' : 'text-red-400 bg-red-500/10' }} font-semibold px-2 py-0.5 rounded text-xs uppercase tracking-wider">{{ ucfirst($sub->status) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm bg-gray-900/50 p-2 rounded-lg border border-gray-800/50">
                                    <span class="text-gray-400 font-medium">Expiry</span>
                                    <span class="text-gray-200">{{ $sub->expired_at ? $sub->expired_at->format('d M Y') : 'Lifetime' }}</span>
                                </div>
                                @php $latest = $sub->deployments->last(); @endphp
                                <div class="flex justify-between items-center text-sm bg-gray-900/50 p-2 rounded-lg border border-gray-800/50">
                                    <span class="text-gray-400 font-medium">Latest Build</span>
                                    <span class="text-gray-200">{{ $latest ? 'v'.$latest->version : 'None' }}</span>
                                </div>
                            </div>

                            <a href="{{ route('client.portal', $sub->id) }}" class="btn-primary w-full text-center py-2.5 shadow-[0_0_15px_rgba(94,106,210,0.3)] hover:scale-[1.02] transition-transform relative z-10 font-medium flex justify-center items-center gap-2">
                                Manage Plan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @elseif($available_slots > 0)
                <!-- Empty State for Users with Plan but No Subdomain -->
                <div class="glass-panel p-12 flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-primary-500/5 to-transparent pointer-events-none"></div>
                    <div class="relative z-10 max-w-lg">
                        <div class="w-20 h-20 bg-primary-500/10 rounded-2xl flex items-center justify-center mb-6 mx-auto border border-primary-500/20 shadow-lg shadow-primary-500/5">
                            <svg class="w-10 h-10 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-100 mb-3">Welcome to Subly!</h3>
                        <p class="text-gray-400 mb-8 leading-relaxed">
                            Your hosting plan is active and ready to go! Choose your unique subdomain below to claim your space on the web.
                        </p>
                        
                        <form action="{{ route('client.subdomains.store') }}" method="POST" class="max-w-md mx-auto">
                            @csrf
                            <div class="flex flex-col gap-4">
                                <div class="relative group">
                                    <div class="flex flex-col sm:flex-row items-center bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 sm:py-0 focus-within:ring-2 focus-within:ring-primary-500/50 focus-within:border-primary-500 transition-all">
                                        <input type="text" name="name" 
                                            class="bg-transparent border-none p-0 focus:ring-0 text-gray-100 font-mono text-lg flex-1 w-full text-center sm:text-left sm:py-3" 
                                            placeholder="your-project-name" required
                                            pattern="[a-zA-Z0-9\-_]+" title="Only letters, numbers, dashes, and underscores allowed">
                                        <span class="text-gray-500 font-mono font-medium border-t sm:border-t-0 sm:border-l border-gray-800 pt-2 sm:pt-0 sm:pl-4 sm:ml-2 w-full sm:w-auto text-center sm:text-left">{{ config('app.subdomain_suffix') }}</span>
                                    </div>
                                    @error('name')
                                        <p class="text-red-400 text-xs mt-2 text-left bg-red-400/5 py-1 px-3 rounded border border-red-400/10">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="btn-primary py-3.5 px-8 shadow-[0_0_20px_rgba(94,106,210,0.3)] flex items-center justify-center gap-2 font-bold text-base group animate-pulsar hover:animate-none">
                                    Claim This Subdomain
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                                <p class="text-[10px] text-gray-500 mt-2">
                                    *By claiming, you agree to our terms of service. You can claim only one subdomain per plan.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <!-- Empty State for Users without Plan -->
                <div class="glass-panel p-12 flex flex-col items-center text-center relative overflow-hidden hover-lift">
                    <div class="absolute inset-0 bg-gradient-to-b from-gray-800/20 to-transparent pointer-events-none"></div>
                    <div class="relative z-10 max-w-lg">
                        <div class="w-20 h-20 bg-gray-900 rounded-2xl flex items-center justify-center mb-6 mx-auto border border-gray-800 shadow-lg shadow-gray-900/50">
                            <svg class="w-10 h-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-100 mb-3">Welcome to Subly!</h3>
                        <p class="text-gray-400 mb-8 leading-relaxed">
                            You don't have an active hosting plan yet. Get started by exploring our affordable plans to launch your project on the web.
                        </p>
                        
                        <a href="{{ route('client.plans.index') }}" class="btn-primary py-3.5 px-8 shadow-[0_0_20px_rgba(94,106,210,0.3)] inline-flex items-center justify-center gap-2 font-bold text-base group">
                            View Hosting Plans
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Database Info -->
            @if($subdomains->count() > 0)
                <div class="glass-panel overflow-hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 border-b border-gray-800/50 bg-gray-900/30 gap-4">
                        <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                            Database Credentials
                        </h3>
                        <a href="https://db.subly.my.id" target="_blank" class="btn-secondary text-xs py-1.5 px-3 flex items-center gap-2 border-gray-700 hover:bg-gray-700/50 shadow-lg shadow-gray-900/20">
                            <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            <span>Access Database (phpMyAdmin)</span>
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($subdomains as $sub)
                                @foreach($sub->userDatabases as $db)
                                    <div class="relative bg-gray-900/80 rounded-xl p-5 border border-gray-800 shadow-lg group hover:border-gray-700 transition-colors">
                                        <div class="absolute top-0 right-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="copyToClipboard('Host: localhost\nDB: {{ $db->db_name }}\nUser: {{ $db->db_user }}\nPass: {{ $db->db_password }}', this)" class="text-gray-500 hover:text-primary-400 transition-all duration-200" title="Copy All Credentials">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </div>
                                        <h4 class="font-medium text-gray-200 flex items-center mb-4 pb-3 border-b border-gray-800">
                                            {{ $sub->full_domain }}
                                        </h4>
                                        <ul class="text-sm space-y-3">
                                            <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800 group/item">
                                                <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">DB Name</span> 
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-300 font-mono text-[13px]">{{ $db->db_name }}</span>
                                                    <button onclick="copyToClipboard('{{ $db->db_name }}', this)" class="text-gray-600 hover:text-primary-400 transition-all duration-200" title="Copy DB Name">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800 group/item">
                                                <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">User</span> 
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-300 font-mono text-[13px]">{{ $db->db_user }}</span>
                                                    <button onclick="copyToClipboard('{{ $db->db_user }}', this)" class="text-gray-600 hover:text-primary-400 transition-all duration-200" title="Copy Username">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800 group/item">
                                                <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Password</span> 
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-300 font-mono text-[13px]">{{ $db->db_password }}</span>
                                                    <button onclick="copyToClipboard('{{ $db->db_password }}', this)" class="text-gray-600 hover:text-primary-400 transition-all duration-200" title="Copy Password">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800 group/item">
                                                <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Host</span> 
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-300 font-mono text-[13px]">localhost</span>
                                                    <button onclick="copyToClipboard('localhost', this)" class="text-gray-600 hover:text-primary-400 transition-all duration-200" title="Copy Host">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path><path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function copyToClipboard(text, btn) {
            const copy = () => {
                if (navigator.clipboard) {
                    return navigator.clipboard.writeText(text);
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.left = "-9999px";
                    textArea.style.top = "0";
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        textArea.remove();
                        return Promise.resolve();
                    } catch (err) {
                        textArea.remove();
                        return Promise.reject(err);
                    }
                }
            };

            copy().then(() => {
                const copyIcon = btn.querySelector('.copy-icon');
                const checkIcon = btn.querySelector('.check-icon');
                
                if (typeof window.showToast === 'function') {
                    window.showToast('Copied to clipboard!');
                }
                
                if (copyIcon && checkIcon) {
                    copyIcon.classList.add('hidden');
                    checkIcon.classList.remove('hidden');
                    btn.classList.add('text-green-400');
                    btn.classList.remove('text-gray-500', 'text-gray-600');
                    
                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                        btn.classList.remove('text-green-400');
                        btn.classList.add(btn.classList.contains('text-gray-600') ? 'text-gray-600' : 'text-gray-500');
                    }, 2000);
                }
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</x-app-layout>
