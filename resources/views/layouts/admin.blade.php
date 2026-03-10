<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subly') }} | Admin Dashboard</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#09090b] text-gray-200 selection:bg-primary-500 selection:text-white" x-data="{ sidebarOpen: false }">
        
        <!-- Background Ambient Glow -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-primary-500/10 blur-[120px]"></div>
            <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-purple-500/10 blur-[120px]"></div>
        </div>

        <div class="min-h-screen flex h-screen overflow-hidden">
            <!-- Sidebar Navigation -->
            <aside class="w-64 flex-shrink-0 bg-gray-900/50 backdrop-blur-md border-r border-gray-800 flex flex-col z-40 fixed sm:relative h-full transition-transform duration-300 ease-in-out sm:translate-x-0"
                   :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
                
                <!-- Logo -->
                <div class="h-16 flex items-center px-6 border-b border-gray-800 shrink-0">
                    <a href="{{ route('admin.index') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 rounded bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:shadow-primary-500/40 transition-all">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white group-hover:text-gray-200 transition-colors">Subly</span>
                    </a>
                </div>

                <!-- Navigation Links Categorized -->
                <div class="flex-1 overflow-y-auto px-4 py-6 space-y-8 scrollbar-hide">
                    
                    <!-- Main Group -->
                    <div>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    {{ __('Overview') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Infrastructure Group -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">Infrastructure</h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('admin.deployments.index') }}" class="sidebar-link {{ request()->routeIs('admin.deployments.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    {{ __('Deployments') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.subdomains.index') }}" class="sidebar-link {{ request()->routeIs('admin.subdomains.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    {{ __('Subdomains') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.databases.index') }}" class="sidebar-link {{ request()->routeIs('admin.databases.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                    {{ __('Databases') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Billing Group -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">Billing</h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('admin.plans.index') }}" class="sidebar-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    {{ __('Plans') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Payments') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.vouchers.index') }}" class="sidebar-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                    {{ __('Vouchers') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Customers Group -->
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">CRM</h4>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ __('Clients') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.chat.index') }}" class="sidebar-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    {{ __('Live Chat') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.feedback.index') }}" class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    {{ __('Testimonials') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.notifications.index') }}" class="sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    {{ __('Notifications') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 sm:hidden" style="display: none;"></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10 w-full backdrop-blur-[2px]">
                
                <!-- Top Navbar (Mobile Hamburger & User Profile) -->
                <header class="h-16 border-b border-gray-800 bg-gray-900/30 backdrop-blur-md flex items-center justify-between px-4 sm:px-6 shrink-0 relative z-20">
                    
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="sm:hidden text-gray-400 hover:text-white focus:outline-none p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-dropdown align="right" width="80" contentClasses="py-1 bg-gray-900 border border-gray-800 shadow-2xl">
                            <x-slot name="trigger">
                                <button class="relative text-gray-400 hover:text-white transition-colors focus:outline-none">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                        </span>
                                    @endif
                                </button>
                            </x-slot>
                        
                            <x-slot name="content">
                                <div class="px-4 py-3 border-b border-gray-800 flex justify-between items-center bg-gray-950/30">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-sm font-medium text-gray-200 hover:text-primary-400 transition-colors flex items-center gap-1">
                                        Notifications
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <form method="POST" action="{{ route('notifications.readAll') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-primary-400 hover:text-primary-300">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="px-4 py-3 border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors {{ is_null($notification->read_at) ? 'bg-gray-800/10' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <p class="text-sm text-gray-300 leading-snug">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                @if(is_null($notification->read_at))
                                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="ml-2 shrink-0">
                                                        @csrf
                                                        <button type="submit" class="w-2 h-2 rounded-full bg-primary-500" title="Mark as read"></button>
                                                    </form>
                                                @endif
                                            </div>
                                            <span class="text-xs text-gray-500 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                            No new notifications.
                                        </div>
                                    @endforelse
                                </div>
                                @if(Auth::user()->notifications->count() > 0)
                                    <div class="px-4 py-2 border-t border-gray-800 text-center">
                                       <form method="POST" action="{{ route('notifications.clearAll') }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-300">Clear all</button>
                                        </form>
                                    </div>
                                @endif
                            </x-slot>
                        </x-dropdown>

                        <x-dropdown align="right" width="48" contentClasses="py-1 bg-gray-900 border border-gray-800 shadow-2xl">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 text-sm font-medium text-gray-300 hover:text-white focus:outline-none transition ease-in-out duration-150 rounded-full bg-gray-800/50 px-3 py-1.5 border border-gray-700/50 hover:border-gray-600">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-r from-gray-700 to-gray-600 flex items-center justify-center text-xs font-bold text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    <span class="hidden sm:inline-block">{{ Auth::user()->name }}</span>
                                    <svg class="fill-current h-4 w-4 text-gray-500 hidden sm:inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Switch App View -->
                                <x-dropdown-link :href="route('client.index')" class="hover:bg-gray-800 text-gray-300 hover:text-white">
                                    {{ __('Client View') }}
                                </x-dropdown-link>
                                
                                <div class="border-t border-gray-800 my-1"></div>
                                
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-300 hover:bg-gray-800 hover:text-white focus:outline-none transition duration-150 ease-in-out">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <div class="px-4 sm:px-6 lg:px-8 py-5 border-b border-gray-800/60 bg-transparent flex flex-col gap-2 shrink-0">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Page Content Scrollable -->
                <main class="flex-1 overflow-y-auto w-full relative animate-fade-in p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Toast Container -->
        <div id="toast-container" class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none"></div>

        <style>
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fade-in 0.5s ease-out forwards;
            }
            .glass-panel {
                @apply bg-gray-900/40 backdrop-blur-md border border-gray-800 rounded-2xl hover:border-gray-700 transition-all duration-300;
            }
            .hover-lift {
                @apply transition-transform duration-300 hover:-translate-y-1;
            }
            .toast {
                @apply bg-gray-900 border border-gray-800 text-gray-100 px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 pointer-events-auto animate-fade-in;
            }
        </style>

        <script>
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                if(!container) return;
                
                const toast = document.createElement('div');
                toast.className = `toast border-l-4 ${type === 'success' ? 'border-l-primary-500' : 'border-l-red-500'}`;
                
                const icon = type === 'success' 
                    ? '<svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    : '<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';

                toast.innerHTML = `${icon} <span class="text-sm font-medium">${message}</span>`;
                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            };
        </script>
        @stack('scripts')
    </body>
</html>
