<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            {{ __('Client Portal') }}
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

            <!-- 2. Deployments & Hosted Environments -->
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
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Upload Form -->
                    <div class="lg:col-span-1">
                        <div class="glass-panel p-6 h-full flex flex-col hover-lift">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 rounded-lg bg-gray-800 border border-gray-700 text-primary-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-100">Deploy Code</h3>
                            </div>
                            <form action="{{ route('client.deployments.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">
                                @csrf
                                <div class="mb-5">
                                    <label for="subdomain_id" class="block text-sm font-medium text-gray-300 mb-1.5">Target Destination</label>
                                    <div class="relative">
                                        <select name="subdomain_id" id="subdomain_id" class="input-field appearance-none pr-10">
                                            @foreach($subdomains as $sub)
                                                <option value="{{ $sub->id }}" class="bg-gray-900">{{ $sub->full_domain }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-6 flex-1">
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Project Files (.zip)</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-xl hover:border-primary-500/50 hover:bg-gray-800/30 transition-all group relative cursor-pointer" onclick="document.getElementById('zip_file').click()">
                                        <div class="space-y-1 text-center">
                                            <div id="upload-icon-container">
                                                <svg class="mx-auto h-12 w-12 text-gray-500 group-hover:text-primary-400 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="flex text-sm text-gray-400">
                                                <label for="zip_file" class="relative cursor-pointer rounded-md font-medium text-primary-400 hover:text-primary-300 focus-within:outline-none">
                                                    <span id="file-chosen-text">Upload a file</span>
                                                    <input id="zip_file" name="zip_file" type="file" class="sr-only" accept=".zip" required onchange="handleFileSelect(this)">
                                                </label>
                                                <p class="pl-1" id="drop-text">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500" id="file-info-text">ZIP up to 50MB</p>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" id="deploy-btn" class="w-full btn-primary py-2.5 mt-auto transition-all {{ (!$plan) ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:shadow-[0_0_20px_rgba(94,106,210,0.4)]' }} flex items-center justify-center gap-2" {{ (!$plan) ? 'disabled' : '' }}>
                                    <span id="btn-text">{{ !$plan ? 'Subscription Required' : 'Initiate Deployment' }}</span>
                                    <svg id="btn-spinner" class="hidden animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Subdomains List -->
                    <div class="lg:col-span-2">
                        <div class="glass-panel overflow-hidden h-full flex flex-col">
                            <div class="p-6 border-b border-gray-800/50 bg-gray-900/30 flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    Hosted Environments
                                </h3>
                            </div>
                            <div class="overflow-x-auto flex-1 relative group/scroll">
                                <!-- Scroll Indicator (Mobile only) -->
                                <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-gray-950/50 to-transparent pointer-events-none opacity-0 group-hover/scroll:opacity-100 sm:hidden transition-opacity"></div>
                                <table class="w-full text-left">
                                    <thead>
                                        <tr>
                                            <th class="table-th">Domain</th>
                                            <th class="table-th">Status</th>
                                            <th class="table-th text-center">Expiry</th>
                                            <th class="table-th text-right">Latest Build</th>
                                            <th class="table-th text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-800/50">
                                        @foreach($subdomains as $sub)
                                            <tr class="group hover:bg-gray-800/30 transition-colors">
                                                <td class="table-td">
                                                    <a href="https://{{ $sub->full_domain }}" target="_blank" class="font-medium text-primary-400 hover:text-primary-300 hover:underline flex items-center gap-1.5 transition-colors">
                                                        {{ $sub->full_domain }}
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </a>
                                                </td>
                                                <td class="table-td">
                                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-md border 
                                                        {{ $sub->status == 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20 shadow-[0_0_10px_rgba(34,197,94,0.1)]' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                                        {{ ucfirst($sub->status) }}
                                                    </span>
                                                </td>
                                                <td class="table-td text-center">
                                                    @if($sub->expired_at)
                                                        <div class="text-sm {{ $sub->expired_at->isPast() ? 'text-red-400 font-semibold' : 'text-gray-200' }}">
                                                            {{ $sub->expired_at->diffForHumans() }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">{{ $sub->expired_at->format('d M Y') }}</div>
                                                    @else
                                                        <span class="text-gray-500 italic text-sm">Lifetime/None</span>
                                                    @endif
                                                </td>
                                                <td class="table-td text-right">
                                                    @php $latest = $sub->deployments->last(); @endphp
                                                    @if($latest)
                                                        <div class="flex items-center justify-end gap-2">
                                                            <span class="text-xs text-gray-500">v{{ $latest->version }}</span>
                                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full 
                                                                {{ $latest->status === 'success' ? 'bg-green-500/10 text-green-400' : '' }}
                                                                {{ $latest->status === 'queued' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                                                {{ $latest->status === 'processing' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                                                {{ $latest->status === 'error' ? 'bg-red-500/10 text-red-400' : '' }}">
                                                                {{ ucfirst($latest->status) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-sm text-gray-500 italic">No deployments yet</span>
                                                    @endif
                                                </td>
                                                <td class="table-td text-right">
                                                    <div class="flex items-center justify-end gap-3">
                                                        <a href="{{ route('client.subdomains.renew', $sub) }}" class="text-sm font-medium text-primary-400 hover:text-primary-300 transition-colors">Perpanjang</a>
                                                        <form action="{{ route('client.subdomains.destroy', $sub) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin berhenti berlangganan? Subdomain dan seluruh filenya akan dihapus permanen.');" class="inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-400 transition-colors">Berhenti</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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

            <!-- Support & Feedback -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Support Tickets -->
                <div id="support" class="glass-panel p-6 flex flex-col h-full">
                    <h3 class="text-lg font-medium text-gray-100 mb-6 flex items-center gap-2">
                        <div class="p-1.5 rounded bg-primary-500/20 text-primary-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        Help & Support
                    </h3>
                    <form action="{{ route('client.reports.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Subject</label>
                            <input type="text" name="subject" class="input-field" required placeholder="Issue with database connection...">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Detailed Description</label>
                            <textarea name="message" rows="3" class="input-field" required placeholder="Describe what exactly is happening..."></textarea>
                        </div>
                        <button type="submit" class="w-full btn-secondary py-2 border-gray-700 bg-gray-800 hover:bg-gray-700">
                            Submit Support Ticket
                        </button>
                    </form>

                    <div class="mt-8">
                        <h4 class="text-xs uppercase tracking-wider font-semibold text-gray-500 mb-3 ml-1">Recent Tickets</h4>
                        <ul class="space-y-2">
                            @forelse($reports as $report)
                                <li class="p-3 bg-gray-900/50 border border-gray-800 rounded-lg flex justify-between items-center group">
                                    <span class="font-medium text-sm text-gray-300">{{ $report->subject }}</span>
                                    <span class="px-2 py-0.5 inline-flex text-[10px] uppercase font-bold tracking-wider rounded border 
                                        {{ $report->status === 'resolved' ? 'bg-green-500/10 text-green-400 border-green-500/20' : '' }}
                                        {{ $report->status === 'open' ? 'bg-red-500/10 text-red-400 border-red-500/20' : '' }}
                                        {{ $report->status === 'in_progress' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : '' }}">
                                        {{ str_replace('_', ' ', $report->status) }}
                                    </span>
                                </li>
                            @empty
                                <li class="py-4 text-sm text-gray-500 text-center italic border border-gray-800 border-dashed rounded-lg bg-gray-900/30">No active tickets.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Feedback -->
                <div class="glass-panel p-6 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-yellow-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <h3 class="text-lg font-medium text-gray-100 mb-6 flex items-center gap-2 relative z-10">
                        <div class="p-1.5 rounded bg-yellow-500/20 text-yellow-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        Platform Feedback
                    </h3>
                    
                    @if($feedback)
                        <div class="p-5 bg-gray-900/50 rounded-xl border border-gray-800 flex-1 flex flex-col justify-center items-center text-center relative z-10">
                            <div class="flex items-center gap-1 mb-4 bg-gray-950 p-2 rounded-lg border border-gray-800">
                                @for($i = 0; $i < $feedback->rating; $i++)
                                    <svg class="w-5 h-5 text-yellow-500 drop-shadow-[0_0_5px_rgba(234,179,8,0.5)] fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-300 font-medium mb-4">Thanks for shaping Subly's future!</p>
                            @if($feedback->comment)
                                <div class="text-sm text-gray-400 italic bg-gray-800/50 p-4 rounded-lg border border-gray-700/50 w-full relative">
                                    <svg class="w-6 h-6 text-gray-700 absolute top-2 left-2 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" /></svg>
                                    <p class="relative z-10 pl-6 pr-2">{{ $feedback->comment }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('client.feedback.store') }}" method="POST" class="flex-1 flex flex-col relative z-10">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Your Rating</label>
                                <div class="relative">
                                    <select name="rating" class="input-field appearance-none pr-10">
                                        <option value="5" class="bg-gray-900">⭐⭐⭐⭐⭐ Phenomenal</option>
                                        <option value="4" class="bg-gray-900">⭐⭐⭐⭐ Great overall</option>
                                        <option value="3" class="bg-gray-900">⭐⭐⭐ Meets expectations</option>
                                        <option value="2" class="bg-gray-900">⭐⭐ Needs improvement</option>
                                        <option value="1" class="bg-gray-900">⭐ Disappointing</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6 flex-1">
                                <label class="block text-sm font-medium text-gray-400 mb-1">Feedback Notes</label>
                                <textarea name="comment" rows="4" class="input-field h-full resize-none" placeholder="What parts of your experience can we optimize?"></textarea>
                            </div>
                            <button type="submit" class="w-full btn-secondary py-2 border-gray-700 bg-gray-800 hover:bg-gray-700 mt-auto">
                                Share Feedback
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function handleFileSelect(input) {
            const fileNameDisplay = document.getElementById('file-chosen-text');
            const dropText = document.getElementById('drop-text');
            const fileInfoText = document.getElementById('file-info-text');
            const container = input.closest('.border-dashed');
            
            if (input.files.length > 0) {
                const file = input.files[0];
                fileNameDisplay.innerText = file.name;
                dropText.innerText = "";
                fileInfoText.innerText = (file.size / 1024 / 1024).toFixed(2) + " MB";
                container.classList.add('border-primary-500', 'bg-primary-500/5');
                container.classList.remove('border-gray-700');
            }
        }

        // Handle form submission loading state
        document.querySelector('form[action$="deployments"]').addEventListener('submit', function(e) {
            const btn = document.getElementById('deploy-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');
            
            if (btn && btnText && spinner) {
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.innerText = "Uploading Project...";
                spinner.classList.remove('hidden');
            }
        });

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
