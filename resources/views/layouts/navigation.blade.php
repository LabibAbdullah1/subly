<aside class="w-64 flex-shrink-0 bg-gray-900/50 backdrop-blur-md border-r border-gray-800 flex flex-col z-40 fixed xl:relative h-full transition-transform duration-300 ease-in-out xl:translate-x-0"
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-800 shrink-0">
        <a href="{{ route('client.index') }}" class="flex items-center gap-2 group">
            <div class="w-8 h-8 rounded bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:shadow-primary-500/40 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 9h19M2.5 15h19M12 2c3 0 6 4.477 6 10s-3 10-6 10-6-4.477-6-10 3-10 6-10z"></path>
                </svg>
            </div>
            <span class="font-bold text-xl tracking-tight text-white group-hover:text-gray-200 transition-colors">Subly</span>
        </a>
    </div>

    <!-- Navigation Links Categorized -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-8 scrollbar-hide">
        
        <!-- Main Group -->
        <div>
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3 italic">Menu Utama</h4>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('client.index') }}" class="sidebar-link {{ request()->routeIs('client.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zm-10 10a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>
        </div>

        @if(Auth::user()->role === 'Client')
            <!-- Services Group -->
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3 italic">Services</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('client.plans.index') }}" class="sidebar-link {{ request()->routeIs('client.plans.index') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            {{ __('Plan Hosting') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.deployments.index') }}" class="sidebar-link {{ request()->routeIs('client.deployments.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            {{ __('History Deployment') }}
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Communication Group -->
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3 italic">Communication</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('client.chat.index') }}" class="sidebar-link {{ request()->routeIs('client.chat.index') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            {{ __('Live Chat') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.notifications.index') }}" class="sidebar-link {{ request()->routeIs('client.notifications.index') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            {{ __('Notifications') }}
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if(Auth::user()->role === 'Admin')
            <!-- Admin Shortcut -->
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3 italic">Admin</h4>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.index') }}" class="sidebar-link">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            {{ __('Admin Dashboard') }}
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>

    <!-- User Section Bottom -->
    <div class="p-4 border-t border-gray-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all group">
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6-10V7a3 3 0 00-6 0v1" /></svg>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</aside>

<style>
    .sidebar-link {
        @apply flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300;
    }
    .sidebar-link.active {
        @apply bg-primary-500/10 text-primary-400 border border-primary-500/20 shadow-[0_0_15px_rgba(94,106,210,0.1)];
    }
    .sidebar-link:not(.active) {
        @apply text-gray-400 hover:text-white hover:bg-gray-800/50;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
