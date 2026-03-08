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
            
            <!-- Messages -->
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3 shadow-lg" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
            <div class="glass-panel overflow-hidden relative group">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-primary-500/10 rounded-full blur-3xl group-hover:bg-primary-500/20 transition-all duration-700 pointer-events-none"></div>
                <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-semibold text-gray-100">{{ $plan ? $plan->name : 'No Active Plan' }}</h3>
                            @if($plan)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20 shadow-[0_0_10px_rgba(34,197,94,0.1)]">Active</span>
                            @endif
                        </div>
                        @if($payment)
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
                        @endif
                    </div>
                    @if(!$plan)
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('client.plans.index') }}" class="btn-primary shadow-[0_0_15px_rgba(94,106,210,0.3)]">View Hosting Plans</a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Deployments & Upload -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Upload Form -->
                <div class="lg:col-span-1">
                    <div class="glass-panel p-6 h-full flex flex-col">
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
                                    <select name="subdomain_id" id="subdomain_id" class="input-field appearance-none py-2.5 pl-3 pr-10">
                                        @forelse($subdomains as $sub)
                                            <option value="{{ $sub->id }}" class="bg-gray-900">{{ $sub->full_domain }}</option>
                                        @empty
                                            <option value="" class="bg-gray-900">No active domains</option>
                                        @endforelse
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6 flex-1">
                                <label class="block text-sm font-medium text-gray-300 mb-1.5">Project Files (.zip)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-700 border-dashed rounded-xl hover:border-primary-500/50 hover:bg-gray-800/30 transition-all group relative">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-500 group-hover:text-primary-400 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-400">
                                            <label for="zip_file" class="relative cursor-pointer rounded-md font-medium text-primary-400 hover:text-primary-300 focus-within:outline-none">
                                                <span>Upload a file</span>
                                                <input id="zip_file" name="zip_file" type="file" class="sr-only" accept=".zip" required>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">ZIP up to 50MB</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full btn-primary py-2.5 mt-auto transition-all {{ (!$plan || $subdomains->isEmpty()) ? 'opacity-50 cursor-not-allowed grayscale' : 'hover:shadow-[0_0_20px_rgba(94,106,210,0.4)]' }}" {{ (!$plan || $subdomains->isEmpty()) ? 'disabled' : '' }}>
                                {{ !$plan ? 'Subscription Required' : 'Initiate Deployment' }}
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
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full h-full text-left">
                                <thead>
                                    <tr>
                                        <th class="table-th">Domain</th>
                                        <th class="table-th">Status</th>
                                        <th class="table-th text-right">Latest Build</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/50">
                                    @forelse($subdomains as $sub)
                                        <tr class="group hover:bg-gray-800/30 transition-colors">
                                            <td class="table-td">
                                                <a href="http://{{ $sub->full_domain }}" target="_blank" class="font-medium text-primary-400 hover:text-primary-300 hover:underline flex items-center gap-1.5 transition-colors">
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="table-td text-center py-12">
                                                <div class="flex flex-col items-center justify-center text-gray-500 space-y-3">
                                                    <svg class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <p>No environments available.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Info -->
            <div class="glass-panel overflow-hidden">
                <div class="p-6 border-b border-gray-800/50 bg-gray-900/30">
                    <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        Database Credentials
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($subdomains as $sub)
                            @foreach($sub->userDatabases as $db)
                                <div class="relative bg-gray-900/80 rounded-xl p-5 border border-gray-800 shadow-lg group hover:border-gray-700 transition-colors">
                                    <div class="absolute top-0 right-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick="navigator.clipboard.writeText('Host: localhost\nDB: {{ $db->db_name }}\nUser: {{ $db->db_user }}\nPass: {{ $db->db_password }}')" class="text-gray-500 hover:text-primary-400" title="Copy Credentials">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>
                                    <h4 class="font-medium text-gray-200 flex items-center mb-4 pb-3 border-b border-gray-800">
                                        {{ $sub->full_domain }}
                                    </h4>
                                    <ul class="text-sm space-y-3">
                                        <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800">
                                            <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">DB Name</span> 
                                            <span class="text-gray-300 font-mono text-[13px]">{{ $db->db_name }}</span>
                                        </li>
                                        <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800">
                                            <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">User</span> 
                                            <span class="text-gray-300 font-mono text-[13px]">{{ $db->db_user }}</span>
                                        </li>
                                        <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800">
                                            <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Password</span> 
                                            <span class="text-gray-300 font-mono text-[13px]">{{ $db->db_password }}</span>
                                        </li>
                                        <li class="flex justify-between items-center bg-gray-950 px-3 py-1.5 rounded-md border border-gray-800">
                                            <span class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Host</span> 
                                            <span class="text-gray-300 font-mono text-[13px]">localhost</span>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach
                        @empty
                            <div class="col-span-full py-8 text-center text-gray-500 bg-gray-900/50 rounded-xl border border-gray-800 border-dashed">
                                No databases provisioned yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

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
                            <input type="text" name="subject" class="input-field py-2" required placeholder="Issue with database connection...">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-400 mb-1">Detailed Description</label>
                            <textarea name="message" rows="3" class="input-field py-2" required placeholder="Describe what exactly is happening..."></textarea>
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
                                    <select name="rating" class="input-field appearance-none py-2.5 pl-3 pr-10">
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
                                <textarea name="comment" rows="4" class="input-field h-full py-2 resize-none" placeholder="What parts of your experience can we optimize?"></textarea>
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
</x-app-layout>
