<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('Hosting Plans') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 relative select-none">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
            
            <!-- Sleek Page Description Header -->
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white mb-2 tracking-tight">Simple, transparent pricing</h3>
                <p class="text-neutral-400 text-xs sm:text-sm font-medium">All hosting plans include secure subdomains, automated database provisioning, and one-click ZIP deployments. No hidden fees.</p>
            </div>

            <!-- Error Banner -->
            @if(session('error'))
                <div class="bg-neutral-950 border border-red-900/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 shadow-xl max-w-2xl mx-auto mb-8 animate-fade-in" role="alert">
                    <div class="p-1 rounded-lg bg-red-950/20 border border-red-900/30 text-red-400 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    </div>
                    <p class="text-xs font-semibold tracking-wide">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Hosting Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                @forelse($plans as $plan)
                    <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl flex flex-col hover:border-neutral-800 hover:-translate-y-0.5 transition-all duration-300 group shadow-2xl relative overflow-hidden">
                        
                        <!-- Premium Linear-style Subtle Top Outline Accent -->
                        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-neutral-800 to-transparent opacity-80 group-hover:via-neutral-600 transition-opacity"></div>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <!-- Plan Title & Platform Tag -->
                            <div class="flex items-start justify-between mb-4 gap-2">
                                <h4 class="text-base font-bold text-white tracking-tight break-words truncate max-w-[140px]">{{ $plan->name }}</h4>
                                <span class="shrink-0 px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider border {{ $plan->type == 'PHP' ? 'bg-neutral-900 border-neutral-850 text-white' : ($plan->type == 'NodeJS' ? 'bg-neutral-900 border-neutral-850 text-neutral-300' : 'bg-neutral-900 border-neutral-850 text-neutral-400') }}">
                                    {{ $plan->type }}
                                </span>
                            </div>
                            
                            <!-- Price & Term -->
                            <div class="flex items-baseline gap-1.5 mb-5 flex-wrap">
                                <span class="text-2xl font-extrabold text-white">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                <span class="text-xs font-semibold text-neutral-500">/ {{ $plan->duration_months }} mo</span>
                            </div>
                            
                            <!-- Description -->
                            <p class="text-xs text-neutral-400 mb-6 font-medium leading-relaxed min-h-[36px]">
                                {{ $plan->description ?: "Perfect for hosting secure and reliable projects on Subly's infrastructure." }}
                            </p>

                            <!-- Features List -->
                            <div class="space-y-3 text-xs font-medium text-neutral-450 mb-6 flex-1 pt-4 border-t border-neutral-900/60">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    <span class="text-neutral-350 font-bold">{{ $plan->max_storage_mb }}MB SSD Disk</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    <span class="text-neutral-350 font-bold">
                                        @if($plan->max_databases > 0)
                                            Up to {{ $plan->max_databases }} Auto-Databases
                                        @else
                                            No Database Included
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    <span class="text-neutral-350 font-bold">Free Secure Subdomain</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    <span class="text-neutral-350 font-bold">Free SSL Certificate</span>
                                </div>
                            </div>
                            
                            <!-- Checkout Form with optional voucher code -->
                            <form action="{{ route('client.checkout.process', ['plan' => $plan->id] + (request()->has('renew') ? ['renew' => request()->query('renew')] : [])) }}" method="POST" class="mt-auto space-y-3.5 border-t border-neutral-900/60 pt-5">
                                @csrf
                                <div class="relative group/input">
                                    <input type="text" name="voucher_code" placeholder="Voucher Code (Opsional)" 
                                        class="mt-1 block w-full rounded-xl bg-black border border-neutral-850 text-neutral-200 px-3.5 py-2.5 placeholder-neutral-600 focus:border-neutral-500 focus:ring-1 focus:ring-neutral-500 text-xs sm:text-sm transition-all duration-200 outline-none">
                                </div>
                                <button type="submit" class="w-full btn-primary h-12 uppercase font-extrabold text-xs tracking-wider active:scale-[0.98]">
                                    {{ request()->has('renew') ? 'Renew Now' : 'Get Started' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-neutral-950/40 border border-neutral-900 rounded-2xl p-12 text-center border-dashed">
                        <svg class="mx-auto h-10 w-10 text-neutral-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <h3 class="text-sm font-bold text-neutral-350 uppercase tracking-widest">No Plans Available</h3>
                        <p class="text-neutral-500 text-xs mt-2 font-semibold">The administrator has not configured any hosting plans yet. Please check back later.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Sales Enterprise Custom Card -->
            <div class="bg-neutral-950/40 border border-neutral-900 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-6 shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-48 h-48 bg-white/2 rounded-full blur-3xl pointer-events-none"></div>
                <div>
                    <h4 class="text-sm font-extrabold text-white uppercase tracking-wider mb-2">Need a custom enterprise solution?</h4>
                    <p class="text-neutral-450 text-xs sm:text-sm max-w-xl font-medium leading-relaxed">If your application requires dedicated infrastructure, massive SSD capacities, custom database permissions, or high concurrency limits, our support team can build a tailor-made sandbox for you.</p>
                </div>
                <a href="{{ route('client.chat.index') }}" class="btn-secondary h-11 px-6 whitespace-nowrap text-xs uppercase tracking-wider font-bold active:scale-[0.98] inline-flex items-center justify-center">
                    Contact Sales
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
