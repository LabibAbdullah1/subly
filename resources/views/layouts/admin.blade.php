<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="background-color: #000000;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subly') }} | Admin Dashboard</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon-v2.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts / Styles -->
        @include('layouts.assets')
        @livewireStyles
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-black text-neutral-200 selection:bg-neutral-200 selection:text-black overflow-x-hidden" x-data="{ sidebarOpen: false }" style="background-color: #000000;">

        <!-- Background Ambient Glow & Dot Matrix Grid -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none bg-black">
            <!-- Fine SVG Dot Matrix Pattern with Eased Masking to prevent banding -->
            <div class="absolute inset-0 bg-dot-grid [mask-image:radial-gradient(ellipse_120%_120%_at_50%_0%,#000_0%,rgba(0,0,0,0.95)_40%,rgba(0,0,0,0.6)_70%,rgba(0,0,0,0.15)_90%,transparent_100%)] opacity-90"></div>

            <!-- Soft Ambient Radial Glows -->
            <div class="absolute top-[-15%] left-[10%] w-[50%] h-[45%] rounded-full bg-primary-500/18 blur-[140px]"></div>
            <div class="absolute top-[25%] right-[-10%] w-[40%] h-[45%] rounded-full bg-purple-500/14 blur-[130px]"></div>
            <div class="absolute bottom-[-15%] left-[20%] w-[45%] h-[40%] rounded-full bg-primary-600/10 blur-[140px]"></div>
        </div>

        <div class="min-h-screen flex h-screen overflow-hidden">
            <!-- Desktop Sidebar Navigation (Hidden on Mobile) -->
            <aside class="hidden xl:flex w-64 flex-col justify-between bg-black/40 backdrop-blur-md border-r border-neutral-900 h-screen sticky top-0 shrink-0 z-40 select-none">
                <div>
                    <!-- Logo -->
                    <div class="h-16 flex items-center px-6 border-b border-neutral-900/60">
                        <a href="{{ route('admin.index') }}" class="flex items-center gap-2.5 group">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center transition-all group-hover:scale-102">
                                <img class="w-5.5 h-5.5 object-contain" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                            </div>
                            <span class="font-bold text-base tracking-tight text-white group-hover:text-neutral-350 transition-colors">Subly</span>
                            <span class="text-[9px] font-bold bg-neutral-900 border border-neutral-850 px-1.5 py-0.5 rounded text-neutral-400">Admin</span>
                        </a>
                    </div>

                    <!-- Navigation Links Categorized -->
                    <div class="px-4 py-6 space-y-6 overflow-y-auto scrollbar-hide max-h-[calc(100vh-10rem)]">
                        <!-- Overview Group -->
                        <div>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        {{ __('Ringkasan') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Infrastructure Group -->
                        <div>
                            <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Infrastruktur</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="https://arenhost.id/client/clientarea.php" target="_blank" class="sidebar-link">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                        {{ __('Arenhost ID') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.deployments.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.deployments.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        {{ __('Deployment') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.subdomains.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.subdomains.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                        {{ __('Subdomain') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.databases.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.databases.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                        {{ __('Database') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Billing Group -->
                        <div>
                            <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Tagihan</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.plans.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        {{ __('Paket') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.payments.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ __('Pembayaran') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.vouchers.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        {{ __('Voucher') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- CRM Group -->
                        <div>
                            <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">CRM</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.users.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ __('Klien') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.chat.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        {{ __('Live Chat') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.feedback.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.243.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.178 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.772-.567-.373-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z" /></svg>
                                        {{ __('Testimoni') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.notifications.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                        {{ __('Notifikasi') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.reports.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ __('Tiket Dukungan') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.settings.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ __('Pengaturan') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile.edit') }}" wire:navigate class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ __('Pengaturan Akun') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Desktop Sidebar Footer -->
                <div class="p-4 border-t border-neutral-900/60">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-neutral-450 hover:text-red-400 hover:bg-red-500/5 border border-transparent hover:border-red-950/20 transition-all duration-200 active:scale-[0.98] group cursor-pointer">
                            <span class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                {{ __('Keluar') }}
                            </span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Mobile Sticky Bottom Action Tab-Bar (Hidden on Desktop) -->
            <nav class="fixed bottom-0 left-0 right-0 h-16 bg-neutral-950/80 backdrop-blur-xl border-t border-neutral-900/80 z-40 px-6 flex items-center justify-between xl:hidden select-none pb-safe">
                <!-- Home Link -->
                <a href="{{ route('admin.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('admin.index') ? 'text-white' : 'text-neutral-455' }} active:scale-[0.94] transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-[9px] font-bold mt-0.5 tracking-tight">Ringkasan</span>
                </a>

                <!-- Deployments Link -->
                <a href="{{ route('admin.deployments.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('admin.deployments.*') ? 'text-white' : 'text-neutral-455' }} active:scale-[0.94] transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-[9px] font-bold mt-0.5 tracking-tight">Deployment</span>
                </a>

                <!-- Subdomains Link -->
                <a href="{{ route('admin.subdomains.index') }}" wire:navigate class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('admin.subdomains.*') ? 'text-white' : 'text-neutral-455' }} active:scale-[0.94] transition-all">
                    <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <span class="text-[9px] font-bold mt-0.5 tracking-tight">Subdomain</span>
                </a>

                <!-- Menu Link -->
                <button @click="sidebarOpen = true" class="flex flex-col items-center justify-center flex-1 h-full py-1 text-neutral-450 active:scale-[0.94] transition-all focus:outline-none">
                    <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <span class="text-[9px] font-bold mt-0.5 tracking-tight">Menu</span>
                </button>
            </nav>

            <!-- Mobile Drawer Sidebar (Hidden on Desktop) -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 w-[280px] max-w-[85vw] bg-neutral-950 border-r border-neutral-900 z-50 flex flex-col justify-between p-6 shadow-2xl xl:hidden select-none"
                 style="display: none;">

                <div>
                    <!-- Drawer Header -->
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-neutral-900">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-md bg-white flex items-center justify-center">
                                <img class="w-5 h-5 object-contain" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                            </div>
                            <span class="font-bold text-sm tracking-tight text-white">Subly</span>
                            <span class="text-[9px] font-bold bg-neutral-900 border border-neutral-850 px-1.5 py-0.5 rounded text-neutral-400">Admin</span>
                        </div>
                        <button @click="sidebarOpen = false" class="text-neutral-450 hover:text-white p-1 rounded-lg hover:bg-neutral-900 focus:outline-none active:scale-[0.94]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Drawer Links (Minimum Touch Target h-12) -->
                    <div class="space-y-6 overflow-y-auto max-h-[calc(100vh-12rem)] scrollbar-hide">
                        <div>
                            <ul class="space-y-1">
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.index') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                        {{ __('Ringkasan') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Infrastruktur</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.deployments.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.deployments.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        {{ __('Deployment') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.subdomains.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.subdomains.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                        {{ __('Subdomain') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.databases.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.databases.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                        {{ __('Database') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Tagihan</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.plans.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.plans.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        {{ __('Paket') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.payments.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.payments.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ __('Pembayaran') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.vouchers.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.vouchers.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                                        {{ __('Voucher') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">CRM</h4>
                            <ul class="space-y-1">
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.users.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        {{ __('Klien') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.chat.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.chat.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                        {{ __('Live Chat') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.feedback.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.feedback.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.243.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.178 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118l-3.97-2.883c-.772-.567-.373-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z" /></svg>
                                        {{ __('Testimoni') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.notifications.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.notifications.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                        {{ __('Notifikasi') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.reports.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.reports.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        {{ __('Tiket Dukungan') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('admin.settings.index') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.settings.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ __('Pengaturan') }}
                                    </a>
                                </li>
                                <li>
                                    <a @click="sidebarOpen = false" href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('profile.edit') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ __('Pengaturan Akun') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Drawer Footer -->
                <div class="border-t border-neutral-900 pt-4 pb-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 h-12 rounded-xl text-xs font-semibold text-neutral-450 hover:text-red-400 hover:bg-red-500/5 transition-all duration-200 active:scale-[0.98] cursor-pointer">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                            {{ __('Keluar') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Sidebar Backdrop Blur Overlay -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 xl:hidden"
                 style="display: none;"></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10 w-full">

                <!-- Top Navbar (Mobile Hamburger & User Profile) -->
                <header class="h-16 border-b border-neutral-900/60 bg-black/40 backdrop-blur-md flex items-center justify-between px-6 shrink-0 relative z-20">

                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="xl:hidden text-neutral-400 hover:text-white transition-colors focus:outline-none p-2 -ml-2 rounded-lg hover:bg-neutral-900/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"></path>
                            </svg>
                        </button>
                        @isset($header)
                            <div class="hidden xl:flex items-center gap-2 text-xs font-semibold text-neutral-450 tracking-wider uppercase">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <x-dropdown align="right" width="80" contentClasses="py-1 bg-neutral-950 border border-neutral-900 rounded-xl shadow-2xl overflow-hidden">
                            <x-slot name="trigger">
                                <button class="relative p-2 text-neutral-400 hover:text-white rounded-xl hover:bg-neutral-900/40 transition-all focus:outline-none active:scale-[0.98]">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-3 border-b border-neutral-900 flex justify-between items-center bg-neutral-950/80 backdrop-blur-md">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-xs font-bold text-neutral-350 hover:text-white uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                        Notifikasi
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <form method="POST" action="{{ route('notifications.readAll') }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-primary-400 hover:text-primary-300">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-64 overflow-y-auto divide-y divide-neutral-900/40">
                                    @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="px-4 py-3 hover:bg-neutral-900/30 transition-colors {{ is_null($notification->read_at) ? 'bg-neutral-900/10' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <p class="text-xs text-neutral-350 leading-relaxed font-medium">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                @if(is_null($notification->read_at))
                                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="ml-2 shrink-0">
                                                        @csrf
                                                        <button type="submit" class="w-1.5 h-1.5 rounded-full bg-primary-500" title="Mark as read"></button>
                                                    </form>
                                                @endif
                                            </div>
                                            <span class="text-[10px] text-neutral-500 font-medium mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-neutral-500 text-xs font-medium">Tidak ada notifikasi baru.</div>
                                    @endforelse
                                </div>
                                @if(Auth::user()->notifications->count() > 0)
                                    <div class="px-4 py-2 border-t border-neutral-900 text-center">
                                       <form method="POST" action="{{ route('notifications.clearAll') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-300">Hapus semua</button>
                                        </form>
                                    </div>
                                @endif
                            </x-slot>
                        </x-dropdown>

                        <!-- User Profile Menu -->
                        <x-dropdown align="right" width="48" contentClasses="py-1 bg-neutral-950 border border-neutral-900 rounded-xl shadow-2xl">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 text-sm font-medium text-neutral-300 hover:text-white focus:outline-none transition ease-in-out duration-150 rounded-full bg-neutral-900/50 px-3 py-1.5 border border-neutral-850 hover:border-neutral-700 active:scale-[0.98]">
                                    <div class="w-6 h-6 rounded-full bg-neutral-850 flex items-center justify-center text-[10px] font-bold text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    <span class="hidden sm:inline-block text-xs font-semibold">{{ Auth::user()->name }}</span>
                                    <svg class="fill-current h-3.5 w-3.5 text-neutral-500 hidden sm:inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-neutral-900 text-neutral-300 hover:text-white text-xs font-semibold">
                                    {{ __('Pengaturan Akun') }}
                                </x-dropdown-link>

                                <div class="border-t border-neutral-900 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-semibold leading-5 text-neutral-300 hover:bg-neutral-900 hover:text-white focus:outline-none transition duration-150 ease-in-out">
                                        {{ __('Keluar') }}
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                <!-- Page Content Scrollable -->
                <main class="flex-1 overflow-y-auto w-full relative animate-fade-in p-4 sm:p-6 lg:p-8 pb-24 xl:pb-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Toast Container -->
        <div id="toast-container" class="fixed top-20 right-6 z-[100] flex flex-col gap-3 pointer-events-none"></div>

        <style>
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(8px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fade-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .toast {
                background-color: rgba(8, 8, 8, 0.9);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid #121212;
                color: #e5e5e5;
                padding-right: 1.5rem;
                padding-left: 2.25rem;
                padding-top: 1rem;
                padding-bottom: 1rem;
                border-radius: 0.75rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 1.25rem;
                white-space: nowrap;
                pointer-events: auto;
                position: relative;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                animation: toast-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .toast-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 2px;
                background-color: rgba(255, 255, 255, 0.2);
                animation: toast-progress 3s linear forwards;
            }
            @keyframes toast-in {
                from { opacity: 0; transform: translateX(50px) scale(0.9); }
                to { opacity: 1; transform: translateX(0) scale(1); }
            }
            @keyframes toast-progress {
                from { width: 100%; }
                to { width: 0%; }
            }
            .toast-out {
                animation: toast-out 0.4s cubic-bezier(0.7, 0, 0.84, 0) forwards;
            }
            @keyframes toast-out {
                to { opacity: 0; transform: translateX(20px) scale(0.95); }
            }

            /* Custom Premium Dropdown System Styles */
            .custom-dropdown-hidden {
                display: none !important;
            }
            .custom-dropdown {
                position: relative;
            }
            .custom-dropdown-toggle {
                cursor: pointer;
                outline: none !important;
                padding: 0.625rem 1rem !important;
            }
            .custom-dropdown-toggle.text-xs {
                padding: 0.375rem 0.75rem !important;
            }
            .custom-dropdown-toggle:focus {
                border-color: rgba(255, 255, 255, 0.3) !important;
                box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.1) !important;
            }
            .custom-dropdown-menu {
                transform-origin: top;
                transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.15s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .custom-dropdown-item {
                transition: background-color 0.12s ease, color 0.12s ease;
            }
        </style>

        <script>
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                if(!container) return;

                const toast = document.createElement('div');
                toast.className = `toast border-l-2 ${type === 'success' ? 'border-l-white' : 'border-l-red-500'}`;

                const icon = type === 'success'
                    ? '<svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    : '<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';

                toast.innerHTML = `
                    <div class="absolute inset-0 bg-gradient-to-r ${type === 'success' ? 'from-white/5' : 'from-red-500/5'} to-transparent opacity-50 pointer-events-none"></div>
                    ${icon}
                    <span class="text-xs font-semibold tracking-wide relative z-10">${message}</span>
                    <div class="toast-progress ${type === 'success' ? 'bg-white' : 'bg-red-500'}"></div>
                `;
                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('toast-out');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            };

            // Intercept standard browser confirm() dialogs in the capture phase for forms and links
            document.addEventListener('livewire:navigated', function() {
                // 1. Define showCustomConfirmModal globally
                window.showCustomConfirmModal = function(options) {
                    const { title, message, isDelete, onConfirm } = options;

                    // Check if modal already exists to prevent duplicates
                    if (document.getElementById('custom-confirm-modal')) {
                        return;
                    }

                    // Create overlay
                    const overlay = document.createElement('div');
                    overlay.id = 'custom-confirm-modal';
                    overlay.className = 'fixed inset-0 z-[99999] flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-auto';

                    // Create separate backdrop to avoid CSS backdrop-filter blurring the children elements
                    const backdrop = document.createElement('div');
                    backdrop.className = 'absolute inset-0 bg-black/85 backdrop-blur-sm';
                    overlay.appendChild(backdrop);

                    // Create container card
                    const container = document.createElement('div');
                    container.className = 'relative bg-[#0a0a0a] border border-neutral-900 rounded-2xl max-w-md w-full p-6 mx-4 shadow-2xl transform scale-95 transition-all duration-300 flex flex-col overflow-hidden z-10';

                    // Ambient glows
                    const accentGlow = isDelete
                        ? '<div class="absolute -right-16 -top-16 w-36 h-36 bg-red-600/5 rounded-full blur-3xl pointer-events-none"></div>'
                        : '<div class="absolute -right-16 -top-16 w-36 h-36 bg-neutral-500/5 rounded-full blur-3xl pointer-events-none"></div>';

                    // Select styles based on theme/action
                    const iconContainerClass = isDelete
                        ? 'text-red-400 bg-red-500/5 border-red-950/20'
                        : 'text-neutral-200 bg-neutral-900 border-neutral-850';

                    const confirmButtonClass = isDelete
                        ? 'bg-red-950/20 text-red-400 border border-red-900/30 hover:bg-red-900 hover:text-white active:scale-[0.98]'
                        : 'bg-white text-black hover:bg-neutral-200 active:scale-[0.98]';

                    const iconSvg = isDelete
                        ? `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`
                        : `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;

                    container.innerHTML = `
                        ${accentGlow}
                        <div class="flex items-center gap-4 mb-4 relative z-10">
                            <div class="p-2.5 rounded-xl border ${iconContainerClass} flex-shrink-0">
                                ${iconSvg}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white tracking-wide">${title}</h3>
                                <p class="text-[9px] text-neutral-500 font-bold uppercase tracking-wider">Confirm Action</p>
                            </div>
                        </div>
                        <div class="mb-6 text-xs text-neutral-400 leading-relaxed relative z-10">
                            ${message}
                        </div>
                        <div class="flex items-center justify-end gap-3 relative z-10">
                            <button type="button" class="btn-cancel px-4 py-2.5 rounded-xl border border-neutral-850 text-neutral-400 hover:text-white hover:bg-neutral-900 transition-all duration-200 text-xs font-semibold">
                                Cancel
                            </button>
                            <button type="button" class="btn-confirm px-5 py-2.5 rounded-xl ${confirmButtonClass} transition-all duration-200 text-xs font-bold border border-transparent">
                                Confirm
                            </button>
                        </div>
                    `;

                    overlay.appendChild(container);
                    document.body.appendChild(overlay);

                    // Open transition
                    setTimeout(() => {
                        overlay.classList.remove('opacity-0');
                        container.classList.remove('scale-95');
                    }, 10);

                    const closeModal = () => {
                        overlay.classList.add('opacity-0');
                        container.classList.add('scale-95');
                        setTimeout(() => overlay.remove(), 300);
                    };

                    container.querySelector('.btn-cancel').addEventListener('click', closeModal);
                    container.querySelector('.btn-confirm').addEventListener('click', () => {
                        closeModal();
                        if (typeof onConfirm === 'function') {
                            onConfirm();
                        }
                    });
                };

                // 2. Intercept all Form Submissions in capture phase
                document.addEventListener('submit', function(e) {
                    if (e.target.dataset.confirmed === 'true') {
                        return;
                    }

                    const onsubmitAttr = e.target.getAttribute('onsubmit');
                    if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                        // Prevent submission and inline code execution
                        e.preventDefault();
                        e.stopPropagation();

                        // Extract message
                        let message = "Are you sure you want to proceed?";
                        const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                        if (match && match[1]) {
                            message = match[1];
                        }

                        // Decide title and style
                        let isDeleteAction = onsubmitAttr.toLowerCase().includes('delete') ||
                                             onsubmitAttr.toLowerCase().includes('destroy') ||
                                             onsubmitAttr.toLowerCase().includes('hapus') ||
                                             onsubmitAttr.toLowerCase().includes('cancel') ||
                                             onsubmitAttr.toLowerCase().includes('berhenti');

                        let titleText = "Confirm Action";
                        if (onsubmitAttr.toLowerCase().includes('berhenti') || onsubmitAttr.toLowerCase().includes('langganan')) {
                            titleText = "Cancel Subscription";
                        } else if (isDeleteAction) {
                            titleText = "Permanent Deletion";
                        }

                        window.showCustomConfirmModal({
                            title: titleText,
                            message: message,
                            isDelete: isDeleteAction,
                            onConfirm: () => {
                                e.target.dataset.confirmed = 'true';
                                e.target.submit();
                            }
                        });
                    }
                }, true);

                // 3. Intercept all Anchor clicks in capture phase
                document.addEventListener('click', function(e) {
                    const anchor = e.target.closest('a');
                    if (anchor) {
                        if (anchor.dataset.confirmed === 'true') {
                            return;
                        }
                        const onclickAttr = anchor.getAttribute('onclick');
                        if (onclickAttr && onclickAttr.includes('confirm(')) {
                            e.preventDefault();
                            e.stopPropagation();

                            let message = "Are you sure?";
                            const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                            if (match && match[1]) {
                                message = match[1];
                            }

                            window.showCustomConfirmModal({
                                title: 'Confirm Action',
                                message: message,
                                isDelete: true,
                                onConfirm: () => {
                                    anchor.dataset.confirmed = 'true';
                                    anchor.click();
                                }
                            });
                        }
                    }
                }, true);

                // Global Custom Dropdown Replacer
                window.initCustomDropdowns = function() {
                    const selects = document.querySelectorAll('select:not(.custom-dropdown-hidden):not(.bypass-custom-select)');
                    selects.forEach(select => {
                        if (select.closest('.custom-dropdown') || select.style.display === 'none' || select.classList.contains('custom-dropdown-hidden')) return;

                        // Mark native select and hide it
                        select.classList.add('custom-dropdown-hidden');
                        select.style.display = 'none';

                        // Create wrapper
                        const wrapper = document.createElement('div');
                        wrapper.className = 'custom-dropdown relative';

                        // Copy only safe layout, width, and margin classes to the wrapper
                        const safeClasses = [];
                        select.className.split(/\s+/).forEach(cls => {
                            if (!cls || cls === 'custom-dropdown-hidden') return;

                            const isMargin = cls.startsWith('m-') || cls.startsWith('mt-') || cls.startsWith('mb-') || cls.startsWith('ml-') || cls.startsWith('mr-') || cls.startsWith('mx-') || cls.startsWith('my-') ||
                                             cls.startsWith('sm:m-') || cls.startsWith('sm:mt-') || cls.startsWith('sm:mb-') || cls.startsWith('sm:ml-') || cls.startsWith('sm:mr-') || cls.startsWith('sm:mx-') || cls.startsWith('sm:my-') ||
                                             cls.startsWith('md:m-') || cls.startsWith('md:mt-') || cls.startsWith('md:mb-') || cls.startsWith('md:ml-') || cls.startsWith('md:mr-') || cls.startsWith('md:mx-') || cls.startsWith('md:my-');

                            const isLayout = cls === 'block' || cls === 'inline-block' || cls === 'inline' || cls === 'flex' || cls === 'inline-flex' || cls === 'grid' || cls === 'hidden' ||
                                             cls.startsWith('sm:block') || cls.startsWith('sm:inline-block') || cls.startsWith('sm:flex') || cls.startsWith('sm:hidden') ||
                                             cls.startsWith('md:block') || cls.startsWith('md:inline-block') || cls.startsWith('md:flex') || cls.startsWith('md:hidden');

                            const isWidth = cls.startsWith('w-') || cls.startsWith('sm:w-') || cls.startsWith('md:w-');

                            if (isMargin || isLayout || isWidth) {
                                  safeClasses.push(cls);
                            }
                        });

                        if (safeClasses.length > 0) {
                            wrapper.className += ' ' + safeClasses.join(' ');
                        }

                        // Ensure we have a width container class if not already added
                        if (!wrapper.className.includes('w-')) {
                            if (select.classList.contains('w-full')) {
                                wrapper.classList.add('w-full');
                            } else {
                                wrapper.classList.add('w-auto');
                            }
                        }

                        // Create toggle button
                        const toggleBtn = document.createElement('button');
                        toggleBtn.type = 'button';

                        let btnClasses = 'custom-dropdown-toggle w-full flex items-center justify-between bg-neutral-950 border border-neutral-900 rounded-xl text-neutral-200 hover:border-neutral-700 hover:bg-neutral-900 focus:outline-none transition-all font-semibold select-none cursor-pointer ';

                        if (select.className.includes('py-1') || select.className.includes('py-0.5') || select.className.includes('text-[10px]') || select.className.includes('text-xs')) {
                            btnClasses += 'px-3 py-1.5 text-xs';
                        } else if (select.className.includes('py-2') || select.className.includes('py-2.5')) {
                            btnClasses += 'px-3.5 py-2.5 text-xs sm:text-sm';
                        } else {
                            btnClasses += 'px-4 py-3 text-xs sm:text-sm';
                        }
                        toggleBtn.className = btnClasses;

                        const labelSpan = document.createElement('span');
                        labelSpan.className = 'custom-dropdown-label truncate mr-2';

                        const initialOption = select.options[select.selectedIndex] || select.options[0];
                        labelSpan.textContent = initialOption ? initialOption.textContent : 'Select...';

                        const chevronSvg = document.createElement('div');
                        chevronSvg.className = 'transition-transform duration-200 text-neutral-500 flex-shrink-0 flex items-center justify-center';
                        chevronSvg.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>`;

                        toggleBtn.appendChild(labelSpan);
                        toggleBtn.appendChild(chevronSvg);

                        // Create menu
                        const menu = document.createElement('div');
                        menu.className = 'custom-dropdown-menu absolute left-0 right-0 mt-1.5 bg-neutral-950 border border-neutral-900 rounded-xl shadow-2xl py-1.5 z-[99999] opacity-0 scale-95 pointer-events-none transition-all duration-200 origin-top max-h-60 overflow-y-auto scrollbar-thin';

                        Array.from(select.options).forEach((opt, idx) => {
                            const item = document.createElement('div');
                            item.className = 'custom-dropdown-item px-4 py-2.5 text-xs sm:text-sm text-neutral-300 hover:bg-neutral-900 hover:text-white cursor-pointer transition-colors font-medium select-none truncate';
                            if (opt.selected) {
                                item.className += ' bg-neutral-900 text-white font-semibold';
                            }
                            if (opt.disabled) {
                                item.className += ' opacity-50 cursor-not-allowed pointer-events-none';
                            }
                            item.textContent = opt.textContent;
                            item.dataset.value = opt.value;

                            item.addEventListener('click', (e) => {
                                e.stopPropagation();
                                if (opt.disabled) return;

                                select.selectedIndex = idx;

                                menu.querySelectorAll('.custom-dropdown-item').forEach(el => el.classList.remove('bg-neutral-900', 'text-white', 'font-semibold'));
                                item.classList.add('bg-neutral-900', 'text-white', 'font-semibold');

                                labelSpan.textContent = opt.textContent;
                                closeMenu();

                                select.dispatchEvent(new Event('change', { bubbles: true }));
                            });

                            menu.appendChild(item);
                        });

                        const openMenu = () => {
                            document.querySelectorAll('.custom-dropdown-menu').forEach(m => {
                                if (m !== menu) {
                                    m.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                                    m.classList.remove('opacity-100', 'scale-100');
                                    const c = m.previousElementSibling?.querySelector('div');
                                    if (c) c.style.transform = 'rotate(0deg)';
                                }
                            });

                            menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                            menu.classList.add('opacity-100', 'scale-100');
                            chevronSvg.style.transform = 'rotate(180deg)';
                        };

                        const closeMenu = () => {
                            menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                            menu.classList.remove('opacity-100', 'scale-100');
                            chevronSvg.style.transform = 'rotate(0deg)';
                        };

                        toggleBtn.addEventListener('click', (e) => {
                            e.stopPropagation();
                            const isOpen = !menu.classList.contains('pointer-events-none');
                            if (isOpen) {
                                closeMenu();
                            } else {
                                openMenu();
                            }
                        });

                        document.addEventListener('click', () => {
                            closeMenu();
                        });

                        // Insert custom element structure
                        select.parentNode.insertBefore(wrapper, select);
                        wrapper.appendChild(select);
                        wrapper.appendChild(toggleBtn);
                        wrapper.appendChild(menu);
                    });
                };

                // Run replacer on load and check periodically for dynamically added dropdowns
                window.initCustomDropdowns();
                setInterval(window.initCustomDropdowns, 800);
            });
        </script>
        @livewireScripts
        @stack('scripts')
    </body>
</html>
