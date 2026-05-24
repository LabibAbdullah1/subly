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
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
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
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                {{ __('Paket Hosting') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.deployments.index') }}" class="sidebar-link {{ request()->routeIs('client.deployments.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
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
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                {{ __('Live Chat') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.notifications.index') }}" class="sidebar-link {{ request()->routeIs('client.notifications.index') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                {{ __('Notifikasi') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.reports.index') }}" class="sidebar-link {{ request()->routeIs('client.reports.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
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
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        <span class="text-[9px] font-bold mt-0.5 tracking-tight">Beranda</span>
    </a>

    @if(Auth::user()->role === 'Client')
        <!-- Plans Link -->
        <a href="{{ route('client.plans.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('client.plans.index') ? 'text-white' : 'text-neutral-450' }} active:scale-[0.94] transition-all">
            <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span class="text-[9px] font-bold mt-0.5 tracking-tight">Paket</span>
        </a>

        <!-- Deployments Link -->
        <a href="{{ route('client.deployments.index') }}" class="flex flex-col items-center justify-center flex-1 h-full py-1 {{ request()->routeIs('client.deployments.*') ? 'text-white' : 'text-neutral-450' }} active:scale-[0.94] transition-all">
            <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
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
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                {{ __('Paket Hosting') }}
                            </a>
                        </li>
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.deployments.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.deployments.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
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
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                                {{ __('Live Chat') }}
                            </a>
                        </li>
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.notifications.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.notifications.index') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                                {{ __('Notifikasi') }}
                            </a>
                        </li>
                        <li>
                            <a @click="sidebarOpen = false" href="{{ route('client.reports.index') }}" class="flex items-center gap-3.5 px-4 h-12 rounded-xl text-xs font-semibold {{ request()->routeIs('client.reports.*') ? 'bg-neutral-900 text-white' : 'text-neutral-400 hover:text-white hover:bg-neutral-900/40' }} active:scale-[0.98] transition-all">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
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
