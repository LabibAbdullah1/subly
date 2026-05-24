<!-- Desktop Sidebar Navigation (Hidden on Mobile) -->
<aside class="hidden xl:flex w-64 flex-col justify-between bg-neutral-950/20 backdrop-blur-xl border-r border-neutral-900/60 h-screen sticky top-0 shrink-0 z-40 select-none">
    <div>
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-neutral-900">
            <a href="{{ route('client.index') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center transition-all group-hover:scale-102">
                    <img class="w-5.5 h-5.5 object-contain" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                </div>
                <span class="font-bold text-base tracking-tight text-white group-hover:text-neutral-350 transition-colors">Subly</span>
            </a>
        </div>

        <!-- Desktop Links -->
        <div class="px-4 py-6 space-y-6">
            <!-- Main Group -->
            <div>
                <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Menu Utama</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('client.index') }}" class="sidebar-link {{ request()->routeIs('client.index') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            {{ __('Beranda') }}
                        </a>
                    </li>
                </ul>
            </div>

            @if(Auth::user()->role === 'Client')
                <!-- Services Group -->
                <div>
                    <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Layanan</h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('client.plans.index') }}" class="sidebar-link {{ request()->routeIs('client.plans.index') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3V7.5a3 3 0 013-3h13.5a3 3 0 013 3v3.75a3 3 0 01-3 3zm-13.5 0v3.75a3 3 0 003 3h13.5a3 3 0 003-3v-3.75m-16.5 0H18" /></svg>
                                {{ __('Paket Hosting') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.deployments.index') }}" class="sidebar-link {{ request()->routeIs('client.deployments.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ __('Riwayat Deployment') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Communication Group -->
                <div>
                    <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Komunikasi</h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('client.chat.index') }}" class="sidebar-link {{ request()->routeIs('client.chat.index') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0zM16.5 15.25c0-1.035-.84-1.875-1.875-1.875H13.5a1.5 1.5 0 01-1.5-1.5V12m-8.625 3.25c0-1.42 1.155-2.575 2.575-2.575h2.25a2.575 2.575 0 012.575 2.575M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ __('Live Chat') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.notifications.index') }}" class="sidebar-link {{ request()->routeIs('client.notifications.index') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                {{ __('Notifikasi') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.reports.index') }}" class="sidebar-link {{ request()->routeIs('client.reports.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                                {{ __('Pusat Dukungan') }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if(Auth::user()->role === 'Admin')
                <!-- Admin Shortcut -->
                <div>
                    <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Admin</h4>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.index') }}" class="sidebar-link">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                                {{ __('Dashboard Admin') }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Desktop Sidebar Footer (User details & Logout) -->
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

<!-- Mobile Navigation Sticky Bottom Tab Bar (Hidden on Desktop) -->
<nav class="fixed bottom-0 left-0 right-0 h-16 bg-black border-t border-neutral-900 z-40 px-6 flex items-center justify-between xl:hidden select-none pb-safe shadow-[0_-8px_30px_rgba(0,0,0,0.6)]">
    <!-- Dashboard Link -->
    <a href="{{ route('client.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('client.index') ? 'text-white' : 'text-neutral-450' }} active:scale-[0.94] transition-all">
        <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        <span class="text-[9px] font-bold mt-0.5 tracking-tight">Beranda</span>
    </a>

    @if(Auth::user()->role === 'Client')
        <!-- Plans Link -->
        <a href="{{ route('client.plans.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('client.plans.index') ? 'text-white' : 'text-neutral-450' }} active:scale-[0.94] transition-all">
            <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3V7.5a3 3 0 013-3h13.5a3 3 0 013 3v3.75a3 3 0 01-3 3zm-13.5 0v3.75a3 3 0 003 3h13.5a3 3 0 003-3v-3.75m-16.5 0H18" />
            </svg>
            <span class="text-[9px] font-bold mt-0.5 tracking-tight">Paket</span>
        </a>

        <!-- Deployments Link -->
        <a href="{{ route('client.deployments.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('client.deployments.*') ? 'text-white' : 'text-neutral-455' }} active:scale-[0.94] transition-all">
            <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-[9px] font-bold mt-0.5 tracking-tight">Deployment</span>
        </a>
    @elseif(Auth::user()->role === 'Admin')
        <!-- Admin Link -->
        <a href="{{ route('admin.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('admin.index') ? 'text-white' : 'text-neutral-450' }} active:scale-[0.94] transition-all">
            <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
            </svg>
            <span class="text-[9px] font-bold mt-0.5 tracking-tight">Admin</span>
        </a>
    @endif

    <!-- Mobile More Drawer Trigger -->
    <button @click="sidebarOpen = true" class="flex flex-col items-center justify-center flex-1 h-full py-1 text-neutral-450 active:scale-[0.94] transition-all focus:outline-none">
        <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
        <span class="text-[9px] font-bold mt-0.5 tracking-tight">Menu</span>
    </button>
</nav>

<!-- Mobile Navigation Drawer Overlay (Slides from Left or Right) -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-250"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 w-[280px] max-w-[85vw] bg-black/95 backdrop-blur-xl border-r border-neutral-900 z-50 flex flex-col justify-between p-6 shadow-[24px_0_50px_rgba(0,0,0,0.9)] xl:hidden select-none"
     style="display: none;">
    
    <div>
        <!-- Drawer Header -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-neutral-900">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-md bg-white flex items-center justify-center">
                    <img class="w-5 h-5 object-contain" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                </div>
                <span class="font-bold text-sm tracking-tight text-white">Subly</span>
            </div>
            <button @click="sidebarOpen = false" class="text-neutral-450 hover:text-white p-1 rounded-lg hover:bg-neutral-900 focus:outline-none active:scale-[0.94]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Drawer Links (Minimum Touch Target h-12) -->
        <div class="space-y-6">
            <div>
                <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Menu Utama</h4>
                <ul class="space-y-1">
                    <li>
                        <a @click="sidebarOpen = false" href="{{ route('client.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.index') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                            {{ __('Beranda') }}
                        </a>
                    </li>
                </ul>
            </div>

            @if(Auth::user()->role === 'Client')
                <div>
                    <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Layanan</h4>
                    <ul class="space-y-1">
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.plans.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.plans.index') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3V7.5a3 3 0 013-3h13.5a3 3 0 013 3v3.75a3 3 0 01-3 3zm-13.5 0v3.75a3 3 0 003 3h13.5a3 3 0 003-3v-3.75m-16.5 0H18" /></svg>
                                {{ __('Paket Hosting') }}
                            </a>
                        </li>
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.deployments.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.deployments.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ __('Riwayat Deployment') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Komunikasi</h4>
                    <ul class="space-y-1">
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.chat.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.chat.index') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0zM16.5 15.25c0-1.035-.84-1.875-1.875-1.875H13.5a1.5 1.5 0 01-1.5-1.5V12m-8.625 3.25c0-1.42 1.155-2.575 2.575-2.575h2.25a2.575 2.575 0 012.575 2.575M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ __('Live Chat') }}
                            </a>
                        </li>
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.notifications.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.notifications.index') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                {{ __('Notifikasi') }}
                            </a>
                        </li>
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.reports.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.reports.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                                {{ __('Pusat Dukungan') }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if(Auth::user()->role === 'Admin')
                <div>
                    <h4 class="text-[9px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-3">Admin</h4>
                    <ul class="space-y-1">
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('admin.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold bg-neutral-900 text-white active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                                {{ __('Dashboard Admin') }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Drawer Footer -->
    <div class="border-t border-neutral-900 pt-4 pb-2">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="w-9 h-9 rounded-full bg-neutral-900 border border-neutral-850 flex items-center justify-center text-xs font-bold text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <div class="truncate">
                <p class="text-xs font-semibold text-neutral-200">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-neutral-500 font-medium truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 h-12 rounded-xl text-xs font-semibold text-neutral-450 hover:text-red-400 hover:bg-red-500/5 transition-all duration-200 active:scale-[0.98] cursor-pointer">
                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</div>
