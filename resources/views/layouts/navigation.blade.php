<nav x-data="{ open: false }" class="bg-gray-900/50 backdrop-blur-md border-b border-gray-800 sticky top-0 z-50">
    @php
        $dashboardRoute = Auth::check() && Auth::user()->role === 'Admin' ? route('admin.index') : route('client.index');
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $dashboardRoute }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 rounded bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:shadow-primary-500/40 transition-all">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 9h19M2.5 15h19M12 2c3 0 6 4.477 6 10s-3 10-6 10-6-4.477-6-10 3-10 6-10z"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white group-hover:text-gray-200 transition-colors">Subly</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('client.index')" :active="request()->routeIs('client.index')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'Client')
                        <x-nav-link :href="route('client.plans.index')" :active="request()->routeIs('client.plans.index')">
                            {{ __('Plan Hosting') }}
                        </x-nav-link>
                        <x-nav-link :href="route('client.deployments.index')" :active="request()->routeIs('client.deployments.*')">
                            {{ __('History') }}
                        </x-nav-link>
                        <x-nav-link :href="route('client.chat.index')" :active="request()->routeIs('client.chat.index')">
                            {{ __('Chat') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center gap-4 border-l border-gray-800 pl-6 ml-2">
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
                            <div class="px-4 py-3 border-b border-gray-800 flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-200">Notifications</span>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.readAll') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-primary-400 hover:text-primary-300">Mark all read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
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
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm leading-5 text-gray-300 hover:bg-gray-800 hover:text-white focus:outline-none focus:bg-gray-800 transition duration-150 ease-in-out">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900 border-b border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('client.index')" :active="request()->routeIs('client.index')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'Client')
                <x-responsive-nav-link :href="route('client.plans.index')" :active="request()->routeIs('client.plans.index')">
                    {{ __('Plan Hosting') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.deployments.index')" :active="request()->routeIs('client.deployments.*')">
                    {{ __('History') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('client.chat.index')" :active="request()->routeIs('client.chat.index')">
                    {{ __('Chat') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-800">
            <div class="px-4">
                <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Override generic nav link component styles here or in a separate file for the Linear feel -->
<style>
    .nav-link {
        @apply inline-flex items-center px-3 py-2 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out h-16;
    }
    .nav-link.active {
        @apply border-primary-500 text-white bg-white/5;
    }
    .nav-link.inactive {
        @apply border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-600 hover:bg-white/5;
    }
</style>
