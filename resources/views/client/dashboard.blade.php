<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-400 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            {{ __('Client Portal Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success / Error Status Alerts -->
            @if (session('success'))
                <div class="bg-neutral-950 border border-neutral-900 text-white px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in shadow-xl" role="alert">
                    <div class="p-1 rounded-lg bg-white/5 border border-white/10 text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-neutral-950 border border-red-900/30 text-red-400 px-4 py-3.5 rounded-xl flex items-start gap-3 shadow-xl" role="alert">
                    <div class="p-1 rounded-lg bg-red-950/20 border border-red-900/30 text-red-400 flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <ul class="list-disc list-inside text-xs font-semibold tracking-wide space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Claim Your Next Subdomain Banner -->
            @if($subdomains->count() > 0 && $available_slots > 0)
                <div class="relative overflow-hidden glass-panel glass-panel-glow p-6 mb-8 flex flex-col lg:flex-row items-center justify-between gap-6 shadow-[0_24px_50px_-12px_rgba(0,0,0,0.85)]">
                    <div class="absolute inset-0 bg-gradient-to-r from-neutral-900/20 to-transparent pointer-events-none"></div>
                    <div class="relative z-10 w-full lg:w-auto text-center lg:text-left">
                        <h3 class="text-base sm:text-lg font-bold text-white mb-1.5 flex items-center justify-center lg:justify-start gap-2 tracking-tight">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Claim Your Next Subdomain
                        </h3>
                        <p class="text-neutral-400 text-xs font-medium">You have <span class="text-white font-bold">{{ $available_slots }}</span> unused subdomain slot(s) available.</p>
                    </div>
                    <div class="relative z-10 w-full lg:w-auto">
                        <form action="{{ route('client.subdomains.store') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            @csrf
                            <div class="relative group flex items-center bg-black border border-neutral-850 rounded-xl px-3.5 py-2.5 w-full sm:w-64 focus-within:border-neutral-500 focus-within:ring-1 focus-within:ring-neutral-500 transition-all duration-200">
                                <input type="text" name="name" class="bg-transparent border-none p-0 focus:ring-0 text-white font-mono text-xs sm:text-sm w-full outline-none" placeholder="project-name" required pattern="[a-zA-Z0-9\-_]+">
                                <span class="text-neutral-500 font-mono text-[10px] sm:text-xs pl-2 ml-2 border-l border-neutral-850 shrink-0 select-none">{{ config('app.subdomain_suffix') }}</span>
                            </div>
                            
                            <!-- Plan Selector Dropdown -->
                            <div class="relative w-full sm:w-auto">
                                <select name="payment_id" class="w-full sm:w-auto bg-black border border-neutral-850 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-neutral-300 font-semibold cursor-pointer outline-none focus:border-neutral-500 focus:ring-1 focus:ring-neutral-500 transition-all select-none">
                                    @foreach($unusedPayments as $p)
                                        <option value="{{ $p->id }}">{{ $p->plan->name }} ({{ $p->plan->max_storage_mb }}MB)</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn-primary px-6 py-2.5 h-11 shadow-[0_4px_12px_rgba(255,255,255,0.06)] active:scale-[0.98] transition-transform w-full sm:w-auto font-bold text-xs uppercase tracking-wider">Claim</button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Active Subdomains (Hosted Environments) Section -->
            @if($subdomains->count() > 0)
                <div class="mb-4">
                    <h3 class="text-xs font-bold text-neutral-450 uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" /></svg>
                        Hosted Environments
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subdomains as $sub)
                        <div class="glass-panel glass-panel-glow p-6 flex flex-col relative overflow-hidden group hover:-translate-y-0.5 transition-all duration-300 shadow-[0_24px_50px_-12px_rgba(0,0,0,0.8)]">
                            
                            <div class="flex items-center gap-3 mb-5 relative z-10">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 border transition-all {{ $sub->status == 'active' ? 'bg-neutral-900 border-neutral-800 text-white' : 'bg-red-950/20 border-red-950/30 text-red-400' }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" /></svg>
                                </div>
                                <h3 class="text-sm font-bold text-white tracking-tight break-all flex-1 truncate">
                                    <a href="https://{{ $sub->full_domain }}" target="_blank" class="hover:text-neutral-350 transition-colors flex items-center gap-1.5">
                                        {{ $sub->full_domain }}
                                        <svg class="w-3.5 h-3.5 text-neutral-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                    </a>
                                </h3>
                            </div>
                            
                            <div class="space-y-2.5 mb-5 flex-1 relative z-10">
                                <div class="flex justify-between items-center bg-black/60 px-4 py-3 rounded-xl border border-neutral-900/60">
                                    <span class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest">Status</span>
                                    <span class="px-2 py-0.5 inline-flex text-[9px] font-bold uppercase tracking-wider rounded-md border {{ $sub->status == 'active' ? 'bg-neutral-900 border-neutral-800 text-white' : 'bg-red-950/20 border-red-900/30 text-red-400' }}">
                                        {{ ucfirst($sub->status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center bg-black/60 px-4 py-3 rounded-xl border border-neutral-900/60">
                                    <span class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest">Expiry</span>
                                    <span class="text-xs font-semibold text-neutral-350">{{ $sub->expired_at ? $sub->expired_at->format('d M Y') : 'Lifetime' }}</span>
                                </div>
                                @php $latest = $sub->deployments->last(); @endphp
                                <div class="flex justify-between items-center bg-black/60 px-4 py-3 rounded-xl border border-neutral-900/60">
                                    <span class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest">Latest Build</span>
                                    <span class="text-xs font-semibold text-neutral-350">{{ $latest ? 'v'.$latest->version : 'None' }}</span>
                                </div>
                            </div>

                            <a href="{{ route('client.portal', $sub->id) }}" class="btn-primary w-full py-2.5 h-11 text-xs uppercase tracking-wider font-extrabold flex justify-center items-center gap-1.5 relative z-10 active:scale-[0.98]">
                                Manage Plan
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @elseif($available_slots > 0)
                <!-- Empty State for Users with Active Plan but No Domain Registered -->
                <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl p-8 sm:p-12 flex flex-col items-center text-center shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-neutral-800 to-transparent pointer-events-none"></div>
                    <div class="relative z-10 max-w-lg w-full">
                        <div class="w-16 h-16 bg-neutral-950 border border-neutral-900 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-xl">
                            <svg class="w-8 h-8 text-neutral-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2 tracking-tight">Welcome to Subly!</h3>
                        <p class="text-neutral-400 text-xs sm:text-sm mb-8 leading-relaxed max-w-sm mx-auto font-medium">
                            Your hosting plan is active and ready to go. Choose a unique subdomain to claim your secure, fast space on the web.
                        </p>
                        
                        <form action="{{ route('client.subdomains.store') }}" method="POST" class="max-w-md mx-auto space-y-4">
                            @csrf
                            <div class="relative group">
                                <div class="flex flex-col sm:flex-row items-center bg-black border border-neutral-850 rounded-xl px-4 py-1.5 sm:py-0 focus-within:border-neutral-500 focus-within:ring-1 focus-within:ring-neutral-500 transition-all duration-200">
                                    <input type="text" name="name" 
                                        class="bg-transparent border-none p-0 focus:ring-0 text-white font-mono text-base flex-1 w-full text-center sm:text-left sm:py-3.5 outline-none" 
                                        placeholder="your-project-name" required
                                        pattern="[a-zA-Z0-9\-_]+" title="Only letters, numbers, dashes, and underscores allowed">
                                    <span class="text-neutral-500 font-mono font-medium border-t sm:border-t-0 sm:border-l border-neutral-850 pt-2 sm:pt-0 sm:pl-4 sm:ml-2 w-full sm:w-auto text-center sm:text-left select-none pb-1.5 sm:pb-0">{{ config('app.subdomain_suffix') }}</span>
                                </div>
                                @error('name')
                                    <p class="text-red-400 text-[10px] mt-2 text-left bg-red-950/20 py-1.5 px-3.5 rounded-lg border border-red-900/30 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Plan Selector Dropdown -->
                            <div class="flex flex-col gap-1.5 text-left w-full">
                                <label class="text-[9px] text-neutral-550 font-bold uppercase tracking-widest pl-1">Pilih Paket Hosting</label>
                                <select name="payment_id" class="w-full bg-black border border-neutral-850 rounded-xl px-4 py-3 text-xs sm:text-sm text-neutral-350 focus:border-neutral-500 focus:ring-1 focus:ring-neutral-500 transition-all font-semibold cursor-pointer outline-none select-none">
                                    @forelse($unusedPayments as $p)
                                        <option value="{{ $p->id }}">{{ $p->plan->name }} ({{ $p->plan->max_storage_mb }}MB SSD - {{ $p->plan->duration_months }} Bulan)</option>
                                    @empty
                                        <option disabled>No purchased plans available</option>
                                    @endforelse
                                </select>
                            </div>

                            <button type="submit" class="btn-primary py-3 px-8 h-12 shadow-[0_4px_20px_rgba(255,255,255,0.08)] flex items-center justify-center gap-2 font-extrabold text-xs uppercase tracking-wider group active:scale-[0.98] w-full">
                                Claim This Subdomain
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                            <p class="text-[9px] text-neutral-500 mt-3 font-semibold">
                                *By claiming, you agree to our terms of service. One subdomain allocation per purchased plan.
                            </p>
                        </form>
                    </div>
                </div>
            @else
                <!-- Empty State for Users with No Hosting Plans -->
                <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl p-8 sm:p-12 flex flex-col items-center text-center shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-neutral-800 to-transparent pointer-events-none"></div>
                    <div class="relative z-10 max-w-lg w-full">
                        <div class="w-16 h-16 bg-neutral-950 border border-neutral-900 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-xl">
                            <svg class="w-8 h-8 text-neutral-450" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v14.25M9 6.5h12m-12 3h12m-12 3h12M9 15.75h12" />
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-white mb-2 tracking-tight">Welcome to Subly!</h3>
                        <p class="text-neutral-400 text-xs sm:text-sm mb-8 leading-relaxed max-w-sm mx-auto font-medium">
                            You don't have any active hosting plans. Get started by purchasing an affordable, premium subdomain-based hosting plan.
                        </p>
                        
                        <a href="{{ route('client.plans.index') }}" class="btn-primary py-3 px-8 h-12 shadow-[0_4px_20px_rgba(255,255,255,0.08)] inline-flex items-center justify-center gap-2 font-extrabold text-xs uppercase tracking-wider group active:scale-[0.98]">
                            View Hosting Plans
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Database Credentials Information -->
            @if($subdomains->count() > 0)
                <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl overflow-hidden shadow-2xl">
                    <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center p-6 border-b border-neutral-900/60 bg-neutral-950/20 gap-4">
                        <h3 class="text-xs font-bold text-neutral-400 uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                            Database Credentials
                        </h3>
                        <a href="https://db.subly.my.id" target="_blank" class="btn-secondary h-11 text-xs py-2.5 px-4.5 flex items-center justify-center gap-2 font-bold uppercase tracking-wider active:scale-[0.98]">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                            <span>Access phpMyAdmin</span>
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($subdomains as $sub)
                                @foreach($sub->userDatabases as $db)
                                    <div class="glass-panel glass-panel-glow p-5 shadow-lg group hover:-translate-y-0.5 transition-all duration-300">
                                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                            <button onclick="copyToClipboard('Host: localhost\nDB: {{ $db->db_name }}\nUser: {{ $db->db_user }}\nPass: {{ $db->db_password }}', this)" class="p-1.5 text-neutral-500 hover:text-white rounded-lg hover:bg-neutral-900 transition-all cursor-pointer" title="Copy All Credentials">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"></path>
                                                    <path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <h4 class="font-bold text-xs text-neutral-400 uppercase tracking-widest mb-4 pb-3 border-b border-neutral-900 flex items-center pr-8 truncate">
                                            <a href="https://{{ $sub->full_domain }}" target="_blank" class="hover:text-white transition-colors flex items-center gap-1.5 truncate max-w-full">
                                                {{ $sub->full_domain }}
                                                <svg class="w-3.5 h-3.5 text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                            </a>
                                        </h4>
                                        <ul class="text-xs space-y-2">
                                            <li class="flex items-center justify-between gap-3 bg-black px-4 py-3 rounded-xl border border-neutral-900/80 group/item">
                                                <span class="text-neutral-500 text-[9px] font-bold uppercase tracking-widest select-none">DB Name</span> 
                                                <div class="flex items-center gap-2 truncate">
                                                    <span class="text-neutral-350 font-mono text-xs select-all truncate max-w-[120px] sm:max-w-xs text-right">{{ $db->db_name }}</span>
                                                    <button onclick="copyToClipboard('{{ $db->db_name }}', this)" class="text-neutral-500 hover:text-white transition-colors flex-shrink-0 cursor-pointer" title="Copy DB Name">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"></path>
                                                            <path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="flex items-center justify-between gap-3 bg-black px-4 py-3 rounded-xl border border-neutral-900/80 group/item">
                                                <span class="text-neutral-500 text-[9px] font-bold uppercase tracking-widest select-none">User</span> 
                                                <div class="flex items-center gap-2 truncate">
                                                    <span class="text-neutral-350 font-mono text-xs select-all truncate max-w-[120px] sm:max-w-xs text-right">{{ $db->db_user }}</span>
                                                    <button onclick="copyToClipboard('{{ $db->db_user }}', this)" class="text-neutral-500 hover:text-white transition-colors flex-shrink-0 cursor-pointer" title="Copy Username">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"></path>
                                                            <path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="flex items-center justify-between gap-3 bg-black px-4 py-3 rounded-xl border border-neutral-900/80 group/item">
                                                <span class="text-neutral-500 text-[9px] font-bold uppercase tracking-widest select-none">Password</span> 
                                                <div class="flex items-center gap-2 truncate">
                                                    <span class="text-neutral-350 font-mono text-xs select-all truncate max-w-[120px] sm:max-w-xs text-right">{{ $db->db_password }}</span>
                                                    <button onclick="copyToClipboard('{{ $db->db_password }}', this)" class="text-neutral-500 hover:text-white transition-colors flex-shrink-0 cursor-pointer" title="Copy Password">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"></path>
                                                            <path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </li>
                                            <li class="flex items-center justify-between gap-3 bg-black px-4 py-3 rounded-xl border border-neutral-900/80 group/item">
                                                <span class="text-neutral-500 text-[9px] font-bold uppercase tracking-widest select-none">Host</span> 
                                                <div class="flex items-center gap-2 truncate">
                                                    <span class="text-neutral-350 font-mono text-xs select-all truncate text-right">localhost</span>
                                                    <button onclick="copyToClipboard('localhost', this)" class="text-neutral-500 hover:text-white transition-colors flex-shrink-0 cursor-pointer" title="Copy Host">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path class="copy-icon" stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12.75c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 00-9-9z"></path>
                                                            <path class="check-icon hidden" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                        </svg>
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

    <!-- Provision Stepper Console Overlay -->
    <div id="provision-overlay" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/90 backdrop-blur-xl transition-all duration-300 opacity-0 select-none">
        <div class="bg-neutral-950 border border-neutral-900 rounded-2xl max-w-md w-full p-8 mx-4 shadow-2xl flex flex-col items-center text-center relative overflow-hidden">
            <!-- Faint Ambient Circle -->
            <div class="absolute -right-20 -top-20 w-48 h-48 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Spin Circular Indicator -->
            <div class="relative w-20 h-20 mb-6 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-2 border-dashed border-neutral-800 animate-spin" style="animation-duration: 8s;"></div>
                <div class="absolute inset-2 rounded-full bg-neutral-950 border border-neutral-900 flex items-center justify-center">
                    <svg class="w-8 h-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                    </svg>
                </div>
            </div>

            <h3 class="text-lg font-bold text-white mb-1.5 tracking-tight">Provisioning Environment</h3>
            <p class="text-neutral-400 text-xs mb-8 max-w-xs font-medium">Please wait as we secure, allocate, and configure virtual hosts and MySQL databases for your subdomain.</p>

            <!-- Stepper Container -->
            <div class="w-full space-y-5 relative mb-6">
                <!-- Vertical Timeline Bar -->
                <div class="absolute left-6.5 top-3.5 bottom-3.5 w-0.5 bg-neutral-900 pointer-events-none">
                    <div id="stepper-progress-line" class="w-full bg-white transition-all duration-[600ms] h-0"></div>
                </div>

                <!-- Step 1 -->
                <div class="flex items-start gap-4 text-left relative z-10" id="step-1">
                    <div class="step-icon w-8 h-8 rounded-full border-2 border-neutral-900 bg-neutral-950 flex items-center justify-center flex-shrink-0 transition-all duration-350 text-neutral-500 font-bold text-xs select-none">
                        <span class="step-number">1</span>
                        <svg class="step-spinner hidden w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg class="step-check hidden w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="step-title text-xs font-bold text-neutral-500 transition-colors">Virtual Host Setup</h4>
                        <p class="step-desc text-[10px] text-neutral-500 font-semibold mt-0.5">Creating subdomains and mapping root index directory.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start gap-4 text-left relative z-10" id="step-2">
                    <div class="step-icon w-8 h-8 rounded-full border-2 border-neutral-900 bg-neutral-950 flex items-center justify-center flex-shrink-0 transition-all duration-350 text-neutral-500 font-bold text-xs select-none">
                        <span class="step-number">2</span>
                        <svg class="step-spinner hidden w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg class="step-check hidden w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="step-title text-xs font-bold text-neutral-500 transition-colors">MySQL Schema Allocation</h4>
                        <p class="step-desc text-[10px] text-neutral-500 font-semibold mt-0.5">Allocating secure tablespace and configuring constraints.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start gap-4 text-left relative z-10" id="step-3">
                    <div class="step-icon w-8 h-8 rounded-full border-2 border-neutral-900 bg-neutral-950 flex items-center justify-center flex-shrink-0 transition-all duration-350 text-neutral-500 font-bold text-xs select-none">
                        <span class="step-number">3</span>
                        <svg class="step-spinner hidden w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3.5"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg class="step-check hidden w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="step-title text-xs font-bold text-neutral-500 transition-colors">Grant Privileges</h4>
                        <p class="step-desc text-[10px] text-neutral-500 font-semibold mt-0.5">Authorizing local connections and securing keys.</p>
                    </div>
                </div>
            </div>

            <!-- Terminal-like Console Logs box -->
            <div class="w-full bg-black/60 rounded-xl px-4 py-3 border border-neutral-900 flex items-center gap-2.5 text-left font-mono">
                <span class="relative flex h-1.5 w-1.5 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-white"></span>
                </span>
                <span id="provision-log-text" class="text-[10px] font-bold uppercase tracking-wider text-neutral-350 truncate">Initializing setup...</span>
            </div>
        </div>
    </div>

    <!-- Scripts Layer -->
    <script>
        // Highly ergonomic clipboard copying system
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
                    btn.classList.add('text-white');
                    btn.classList.remove('text-neutral-500');
                    
                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                        btn.classList.remove('text-white');
                        btn.classList.add('text-neutral-500');
                    }, 2000);
                }
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }

        // Stepper animation overlays for provisioning subdomains
        document.addEventListener('DOMContentLoaded', function() {
            const claimForms = document.querySelectorAll('form[action="{{ route("client.subdomains.store") }}"]');
            claimForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    if (button) {
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    }

                    const overlay = document.getElementById('provision-overlay');
                    if (overlay) {
                        overlay.classList.remove('hidden');
                        setTimeout(() => {
                            overlay.classList.remove('opacity-0');
                            overlay.classList.add('opacity-100');
                        }, 50);

                        startProvisioningSteps();
                    }
                });
            });
        });

        function startProvisioningSteps() {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const step3 = document.getElementById('step-3');
            const progressLine = document.getElementById('stepper-progress-line');
            const logText = document.getElementById('provision-log-text');

            const activateStep = (stepEl) => {
                const icon = stepEl.querySelector('.step-icon');
                const num = stepEl.querySelector('.step-number');
                const spinner = stepEl.querySelector('.step-spinner');
                const title = stepEl.querySelector('.step-title');
                
                icon.classList.remove('border-neutral-900', 'text-neutral-500');
                icon.classList.add('border-white', 'text-white', 'shadow-[0_0_15px_rgba(255,255,255,0.15)]');
                if (num) num.classList.add('hidden');
                if (spinner) spinner.classList.remove('hidden');
                if (title) {
                    title.classList.remove('text-neutral-500');
                    title.classList.add('text-white');
                }
            };

            const completeStep = (stepEl) => {
                const icon = stepEl.querySelector('.step-icon');
                const spinner = stepEl.querySelector('.step-spinner');
                const check = stepEl.querySelector('.step-check');
                const desc = stepEl.querySelector('.step-desc');
                const title = stepEl.querySelector('.step-title');
                
                if (spinner) spinner.classList.add('hidden');
                if (check) check.classList.remove('hidden');
                
                icon.classList.remove('shadow-[0_0_15px_rgba(255,255,255,0.15)]', 'border-white', 'text-white');
                icon.classList.add('bg-white', 'border-white', 'text-black', 'shadow-[0_0_12px_rgba(255,255,255,0.2)]');
                
                if (title) {
                    title.classList.remove('text-white');
                    title.classList.add('text-neutral-350');
                }
                if (desc) {
                    desc.classList.remove('text-neutral-500');
                    desc.classList.add('text-neutral-450');
                }
            };

            // Simulate provisioning steps
            // Step 1: 0s - 2.2s
            setTimeout(() => {
                activateStep(step1);
                logText.innerText = "Querying Web Server API...";
                
                setTimeout(() => {
                    logText.innerText = "Provisioning Apache/Nginx VHost...";
                }, 800);

                setTimeout(() => {
                    logText.innerText = "Binding doc_root directory...";
                }, 1500);
            }, 100);

            // Step 2: 2.2s - 4.4s
            setTimeout(() => {
                completeStep(step1);
                progressLine.style.height = '50%';
                
                activateStep(step2);
                logText.innerText = "Connecting to MySQL cluster...";

                setTimeout(() => {
                    logText.innerText = "Generating Database: subly_db_{{ Auth::user()->id }}...";
                }, 3000);

                setTimeout(() => {
                    logText.innerText = "Applying security schemas...";
                }, 3700);
            }, 2200);

            // Step 3: 4.4s - 6.6s
            setTimeout(() => {
                completeStep(step2);
                progressLine.style.height = '100%';

                activateStep(step3);
                logText.innerText = "Creating MySQL user connection...";

                setTimeout(() => {
                    logText.innerText = "GRANT ALL PRIVILEGES ON tablespace...";
                }, 5200);

                setTimeout(() => {
                    logText.innerText = "Encrypting database credentials...";
                }, 6000);
            }, 4400);

            // Complete: 6.6s+
            setTimeout(() => {
                completeStep(step3);
                logText.innerText = "Deployment success! Reloading dashboard...";
                logText.classList.remove('text-neutral-350');
                logText.classList.add('text-white');
            }, 6600);
        }
    </script>
</x-app-layout>
