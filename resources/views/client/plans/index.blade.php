<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            {{ __('Choose Your Hosting Plan') }}
        </h2>
    </x-slot>

    <div class="py-12 relative">
        <!-- Ambient Glow -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-primary-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h3 class="text-3xl font-bold text-gray-100 mb-4 tracking-tight">Simple, transparent pricing</h3>
                <p class="text-gray-400">All plans include secure subdomains, automated database provisioning, and one-click ZIP deployments. No hidden fees.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch pt-4">
                @forelse($plans as $plan)
                    <div class="glass-panel relative flex flex-col hover:-translate-y-2 transition-transform duration-300 group">
                        
                        <!-- Top Accent Bar -->
                        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-gray-800 via-primary-500 to-purple-600 opacity-50 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="p-8 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-xl font-bold text-gray-100">{{ $plan->name }}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $plan->type == 'PHP' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : ($plan->type == 'NodeJS' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20') }}">
                                    {{ $plan->type }}
                                </span>
                            </div>
                            <div class="flex items-baseline gap-1 mb-4">
                                <span class="text-3xl font-bold text-primary-400">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                <span class="text-sm font-medium text-gray-500">/ {{ $plan->duration_months }} month(s)</span>
                            </div>
                            
                            @if($plan->description)
                                <p class="text-sm text-gray-400 mb-8 flex-1">{{ $plan->description }}</p>
                            @else
                                <p class="text-sm text-gray-400 mb-8 flex-1">Perfect for hosting secure and reliable projects on Subly's infrastructure.</p>
                            @endif

                            <div class="space-y-4 text-sm font-medium text-gray-400 mb-8 border-t border-gray-800/60 pt-6">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-gray-200">{{ $plan->max_storage_mb }}MB SSD Storage</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-gray-200">
                                        @if($plan->max_databases > 0)
                                            Up to {{ $plan->max_databases }} Auto-Databases
                                        @else
                                            No Database Included
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-gray-200">Free Secure Subdomain</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-gray-200">Free SSL Certificate</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-gray-200">24/7 Priority Support</span>
                                </div>
                            </div>
                            
                            <form action="{{ route('client.checkout.process', $plan) }}" method="POST" class="mt-auto pt-2 space-y-4">
                                @csrf
                                <div class="relative group/input">
                                    <input type="text" name="voucher_code" placeholder="Voucher Code (Optional)" 
                                        class="w-full bg-gray-900/50 border border-gray-800 rounded-lg py-2 px-3 text-sm focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all text-gray-300 placeholder-gray-600">
                                    <div class="absolute inset-0 rounded-lg bg-primary-500/5 opacity-0 group-focus-within/input:opacity-100 pointer-events-none transition-opacity"></div>
                                </div>
                                <button type="submit" class="w-full btn-primary py-3 hover:shadow-[0_0_20px_rgba(94,106,210,0.5)] bg-gradient-to-r from-primary-600 to-indigo-600">
                                    Select {{ $plan->name }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full glass-panel p-12 text-center border-dashed border border-gray-700/50">
                        <svg class="mx-auto h-12 w-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-300">No Plans Available</h3>
                        <p class="text-gray-500 mt-2">The administrator has not configured any hosting plans yet. Please check back later.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-16 bg-gray-900/40 border border-gray-800 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="text-lg font-medium text-gray-200 mb-2">Need a custom enterprise solution?</h4>
                    <p class="text-gray-400 text-sm max-w-xl">If your application requires significantly more resources, dedicated infrastructure, or more databases, our team can help configure exactly what you need.</p>
                </div>
                <a href="{{ route('client.index') }}#support" class="btn-secondary whitespace-nowrap px-6">
                    Contact Sales
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
