<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="background-color: #000000;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subly') }} | Managed Hosting</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon-v2.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-black text-neutral-200 selection:bg-neutral-200 selection:text-black overflow-x-hidden" x-data="{ sidebarOpen: false }" style="background-color: #000000;">
        
        <!-- Background Ambient Glow & Dot Matrix Grid -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none bg-black">
            <!-- Fine SVG Dot Matrix Pattern -->
            <div class="absolute inset-0 bg-dot-grid [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_80%,transparent_100%)] opacity-70"></div>
            
            <!-- Soft Ambient Radial Glows -->
            <div class="absolute top-[-15%] left-[10%] w-[50%] h-[45%] rounded-full bg-primary-500/7 blur-[140px]"></div>
            <div class="absolute top-[25%] right-[-10%] w-[40%] h-[45%] rounded-full bg-purple-500/5 blur-[130px]"></div>
            <div class="absolute bottom-[-15%] left-[20%] w-[45%] h-[40%] rounded-full bg-primary-600/4 blur-[140px]"></div>
        </div>

        <div class="min-h-screen flex h-screen overflow-hidden">
            <!-- Sidebar Navigation Include -->
            @include('layouts.navigation')
            
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

            <!-- Page Content Area -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10 w-full">
                
                <!-- Top Header -->
                <header class="h-16 border-b border-neutral-900 bg-neutral-950/40 backdrop-blur-xl flex items-center justify-between px-6 shrink-0 relative z-20 shadow-[0_4px_30px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(255,255,255,0.02)]">
                    <div class="flex items-center gap-4">
                        <!-- Desktop/Mobile Sidebar Toggle Menu -->
                        <button @click="sidebarOpen = !sidebarOpen" class="xl:hidden text-neutral-400 hover:text-white transition-colors focus:outline-none p-2 -ml-2 rounded-lg hover:bg-neutral-900/50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"></path>
                            </svg>
                        </button>
                        
                        <!-- Breadcrumb Title -->
                        @isset($header)
                            <div class="hidden xl:flex items-center gap-2 text-xs font-semibold text-neutral-400 tracking-wider uppercase">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Notifications Bell -->
                        <x-dropdown align="right" width="80" contentClasses="py-1 bg-neutral-950 border border-neutral-900 rounded-xl shadow-2xl overflow-hidden">
                            <x-slot name="trigger">
                                <button class="relative p-2 text-neutral-400 hover:text-white rounded-xl hover:bg-neutral-900/40 transition-all focus:outline-none active:scale-[0.98]">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                    </svg>
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
                                    <a href="{{ route('client.notifications.index') }}" class="text-xs font-bold text-neutral-350 hover:text-white uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                        Notifications
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                                <div class="max-h-64 overflow-y-auto divide-y divide-neutral-900/40">
                                    @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="px-4 py-3 hover:bg-neutral-900/30 transition-colors {{ is_null($notification->read_at) ? 'bg-neutral-900/10' : '' }}">
                                            <p class="text-xs text-neutral-350 leading-relaxed font-medium">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                            <span class="text-[10px] text-neutral-500 font-medium mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-neutral-500 text-xs font-medium">No new notifications.</div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>

                        <!-- User Profile Dropdown -->
                        <x-dropdown align="right" width="48" contentClasses="py-1.5 bg-neutral-950 border border-neutral-900 rounded-xl shadow-2xl">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2.5 text-xs font-semibold text-neutral-350 hover:text-white focus:outline-none transition-all active:scale-[0.98] rounded-full bg-neutral-950/60 pl-2.5 pr-3.5 py-1.5 border border-neutral-900/80 hover:border-neutral-800 hover:bg-neutral-900/20">
                                    <div class="w-5.5 h-5.5 rounded-full bg-neutral-900 border border-neutral-850 flex items-center justify-center text-[10px] font-bold text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                    <span class="hidden sm:inline-block max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                    <svg class="fill-current h-3.5 w-3.5 text-neutral-500 hidden sm:inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-neutral-900 mb-1">
                                    <p class="text-[10px] text-neutral-500 font-bold uppercase tracking-wider">Signed in as</p>
                                    <p class="text-xs text-neutral-300 font-semibold truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-xs font-semibold text-neutral-400 hover:bg-red-500/10 hover:text-red-400 focus:outline-none transition duration-150 ease-in-out cursor-pointer">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                @isset($header)
                    <div class="px-6 py-4 border-b border-neutral-900/60 bg-black/20 backdrop-blur-md flex flex-col gap-1 shrink-0 xl:hidden">
                        <div class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest">Navigation</div>
                        <h2 class="text-sm font-bold text-neutral-200">{{ $header }}</h2>
                    </div>
                @endisset

                <!-- Main Scrollable Page Content -->
                <main class="flex-1 overflow-y-auto w-full relative animate-fade-in p-6 sm:p-8 pb-24 xl:pb-8">
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
                animation: fade-in 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            
            /* Premium Linear/Vercel Toast Notifications */
            .toast {
                background-color: #000000;
                border: 1px solid #121212;
                color: #e5e5e5;
                padding-right: 1.5rem;
                padding-left: 2.25rem;
                padding-top: 1rem;
                padding-bottom: 1rem;
                border-radius: 0.75rem;
                box-shadow: 0 24px 50px -12px rgba(0,0,0,0.85), inset 0 1px 0 rgba(255,255,255,0.05);
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 1rem;
                pointer-events: auto;
                position: relative;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                font-family: 'Outfit', 'Inter', sans-serif;
                font-weight: 600;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                animation: toast-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
                from { opacity: 0; transform: translateY(20px) scale(0.95); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }
            @keyframes toast-progress {
                from { width: 100%; }
                to { width: 0%; }
            }
            .toast-out {
                animation: toast-out 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            @keyframes toast-out {
                to { opacity: 0; transform: translateY(-10px) scale(0.95); }
            }

            /* Custom Dropdown Styling Variables */
            .custom-dropdown-hidden {
                display: none !important;
            }
            .custom-dropdown {
                position: relative;
            }
            .custom-dropdown-toggle {
                cursor: pointer;
                outline: none !important;
            }
            .custom-dropdown-menu {
                transform-origin: top;
                transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.15s cubic-bezier(0.16, 1, 0.3, 1);
            }
        </style>

        <script>
            // Global custom premium toast
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast ${type === 'success' ? 'border-l-2 border-l-white' : 'border-l-2 border-l-red-500'}`;
                
                const icon = type === 'success' 
                    ? '<svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>'
                    : '<svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';

                toast.innerHTML = `
                    <div class="absolute inset-0 bg-gradient-to-r ${type === 'success' ? 'from-white/2' : 'from-red-500/2'} to-transparent opacity-50 pointer-events-none"></div>
                    ${icon} 
                    <span class="text-xs font-semibold tracking-wide relative z-10">${message}</span>
                    <div class="toast-progress ${type === 'success' ? 'bg-white' : 'bg-red-500'}"></div>
                `;
                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('toast-out');
                    setTimeout(() => toast.remove(), 400);
                }, 3000);
            };

            // Capture confirm dialogues and turn into stunning custom confirmation modal
            document.addEventListener('livewire:navigated', function() {
                window.showCustomConfirmModal = function(options) {
                    const { title, message, isDelete, onConfirm } = options;

                    if (document.getElementById('custom-confirm-modal')) return;

                    const overlay = document.createElement('div');
                    overlay.id = 'custom-confirm-modal';
                    overlay.className = 'fixed inset-0 z-[99999] flex items-center justify-center transition-all duration-200 opacity-0 pointer-events-auto';
                    
                    const backdrop = document.createElement('div');
                    backdrop.className = 'absolute inset-0 bg-black/80 backdrop-blur-md';
                    overlay.appendChild(backdrop);
                    
                    const container = document.createElement('div');
                    container.className = 'relative bg-neutral-950 border border-neutral-900 rounded-2xl max-w-sm w-full p-6 mx-4 shadow-2xl transform scale-95 transition-all duration-200 flex flex-col z-10 overflow-hidden';
                    
                    // Faint radial glow
                    const accentGlow = isDelete 
                        ? '<div class="absolute -right-16 -top-16 w-36 h-36 bg-red-600/5 rounded-full blur-3xl pointer-events-none"></div>'
                        : '<div class="absolute -right-16 -top-16 w-36 h-36 bg-primary-600/5 rounded-full blur-3xl pointer-events-none"></div>';

                    const iconContainerClass = isDelete 
                        ? 'text-red-400 bg-red-500/10 border-red-500/15' 
                        : 'text-neutral-300 bg-neutral-900 border-neutral-850';

                    const confirmButtonClass = isDelete
                        ? 'bg-red-600 text-white hover:bg-red-500 shadow-[0_4px_20px_rgba(239,68,68,0.15)] active:scale-[0.97]'
                        : 'bg-white text-black hover:bg-neutral-200 shadow-[0_4px_20px_rgba(255,255,255,0.1)] active:scale-[0.97]';

                    const iconSvg = isDelete
                        ? `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`
                        : `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;

                    container.innerHTML = `
                        ${accentGlow}
                        <div class="flex items-center gap-3.5 mb-4 relative z-10">
                            <div class="p-2.5 rounded-xl border ${iconContainerClass} flex-shrink-0">
                                ${iconSvg}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white tracking-tight">${title}</h3>
                                <p class="text-[9px] text-neutral-500 font-bold uppercase tracking-wider">Confirm Action</p>
                            </div>
                        </div>
                        <div class="mb-5 text-xs text-neutral-400 leading-relaxed relative z-10 font-medium">
                            ${message}
                        </div>
                        <div class="flex items-center justify-end gap-2.5 relative z-10">
                            <button type="button" class="btn-cancel px-4 py-2 rounded-xl border border-neutral-850 text-neutral-400 hover:text-white hover:bg-neutral-900 transition-all text-xs font-semibold">
                                Cancel
                            </button>
                            <button type="button" class="btn-confirm px-4.5 py-2 rounded-xl ${confirmButtonClass} transition-all text-xs font-semibold">
                                Confirm
                            </button>
                        </div>
                    `;

                    overlay.appendChild(container);
                    document.body.appendChild(overlay);

                    setTimeout(() => {
                        overlay.classList.remove('opacity-0');
                        container.classList.remove('scale-95');
                    }, 10);

                    const closeModal = () => {
                        overlay.classList.add('opacity-0');
                        container.classList.add('scale-95');
                        setTimeout(() => overlay.remove(), 200);
                    };

                    container.querySelector('.btn-cancel').addEventListener('click', closeModal);
                    container.querySelector('.btn-confirm').addEventListener('click', () => {
                        closeModal();
                        if (typeof onConfirm === 'function') onConfirm();
                    });
                };

                // Intercept submits with confirm
                document.addEventListener('submit', function(e) {
                    if (e.target.dataset.confirmed === 'true') return;

                    const onsubmitAttr = e.target.getAttribute('onsubmit');
                    if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                        e.preventDefault();
                        e.stopPropagation();

                        let message = "Are you sure you want to proceed?";
                        const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                        if (match && match[1]) message = match[1];

                        let isDeleteAction = onsubmitAttr.toLowerCase().includes('delete') || 
                                             onsubmitAttr.toLowerCase().includes('destroy') || 
                                             onsubmitAttr.toLowerCase().includes('hapus') || 
                                             onsubmitAttr.toLowerCase().includes('cancel');

                        let titleText = isDeleteAction ? "Delete Item" : "Confirm Action";

                        window.showCustomConfirmModal({
                            title: titleText,
                            message: message,
                            isDelete: isDeleteAction,
                            onConfirm: () => {
                                e.target.dataset.confirmed = 'true';
                                if (e.target.classList.contains('deprovision-form') && typeof window.startDeprovisioningSteps === 'function') {
                                    window.startDeprovisioningSteps(e.target);
                                } else {
                                    e.target.submit();
                                }
                            }
                        });
                    }
                }, true);

                // Intercept anchor tags
                document.addEventListener('click', function(e) {
                    const anchor = e.target.closest('a');
                    if (anchor) {
                        if (anchor.dataset.confirmed === 'true') return;
                        const onclickAttr = anchor.getAttribute('onclick');
                        if (onclickAttr && onclickAttr.includes('confirm(')) {
                            e.preventDefault();
                            e.stopPropagation();

                            let message = "Are you sure you want to proceed?";
                            const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                            if (match && match[1]) message = match[1];

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

                // Initialize sleek dropdown selectors globally
                window.initCustomDropdowns = function() {
                    const selects = document.querySelectorAll('select:not(.custom-dropdown-hidden)');
                    selects.forEach(select => {
                        if (select.closest('.custom-dropdown') || select.style.display === 'none' || select.classList.contains('custom-dropdown-hidden')) return;

                        select.classList.add('custom-dropdown-hidden');
                        select.style.display = 'none';

                        const wrapper = document.createElement('div');
                        wrapper.className = 'custom-dropdown relative';
                        
                        const safeClasses = [];
                        select.className.split(/\s+/).forEach(cls => {
                            if (!cls || cls === 'custom-dropdown-hidden') return;
                            const isMargin = cls.startsWith('m-') || cls.startsWith('mt-') || cls.startsWith('mb-') || cls.startsWith('ml-') || cls.startsWith('mr-') || cls.startsWith('mx-') || cls.startsWith('my-') || cls.startsWith('sm:m-') || cls.startsWith('sm:mt-') || cls.startsWith('sm:mb-') || cls.startsWith('sm:ml-') || cls.startsWith('sm:mr-') || cls.startsWith('sm:mx-') || cls.startsWith('sm:my-') || cls.startsWith('md:m-') || cls.startsWith('md:mt-') || cls.startsWith('md:mb-') || cls.startsWith('md:ml-') || cls.startsWith('md:mr-') || cls.startsWith('md:mx-') || cls.startsWith('md:my-');
                            const isLayout = cls === 'block' || cls === 'inline-block' || cls === 'inline' || cls === 'flex' || cls === 'inline-flex' || cls === 'grid' || cls === 'hidden' || cls.startsWith('sm:block') || cls.startsWith('sm:inline-block') || cls.startsWith('sm:flex') || cls.startsWith('sm:hidden') || cls.startsWith('md:block') || cls.startsWith('md:inline-block') || cls.startsWith('md:flex') || cls.startsWith('md:hidden');
                            const isWidth = cls.startsWith('w-') || cls.startsWith('sm:w-') || cls.startsWith('md:w-');
                            
                            if (isMargin || isLayout || isWidth) safeClasses.push(cls);
                        });
                        
                        if (safeClasses.length > 0) wrapper.className += ' ' + safeClasses.join(' ');
                        if (!wrapper.className.includes('w-')) wrapper.classList.add(select.classList.contains('w-full') ? 'w-full' : 'w-auto');

                        const toggleBtn = document.createElement('button');
                        toggleBtn.type = 'button';
                        
                        let btnClasses = 'custom-dropdown-toggle w-full flex items-center justify-between bg-neutral-950 border border-neutral-900 rounded-xl text-neutral-205 hover:border-neutral-700 hover:bg-neutral-900/20 focus:outline-none transition-all font-medium select-none cursor-pointer active:scale-[0.98] ';
                        
                        if (select.className.includes('py-1') || select.className.includes('py-0.5') || select.className.includes('text-[10px]') || select.className.includes('text-xs')) {
                            btnClasses += 'px-3 py-1.5 text-xs';
                        } else if (select.className.includes('py-2') || select.className.includes('py-2.5')) {
                            btnClasses += 'px-3.5 py-2 text-xs sm:text-sm';
                        } else {
                            btnClasses += 'px-4 py-2.5 text-xs sm:text-sm';
                        }
                        toggleBtn.className = btnClasses;

                        const labelSpan = document.createElement('span');
                        labelSpan.className = 'custom-dropdown-label truncate mr-2';
                        
                        const initialOption = select.options[select.selectedIndex] || select.options[0];
                        labelSpan.textContent = initialOption ? initialOption.textContent : 'Select...';

                        const chevronSvg = document.createElement('div');
                        chevronSvg.className = 'transition-transform duration-150 text-neutral-500 flex-shrink-0 flex items-center justify-center';
                        chevronSvg.innerHTML = `<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>`;

                        toggleBtn.appendChild(labelSpan);
                        toggleBtn.appendChild(chevronSvg);

                        const menu = document.createElement('div');
                        menu.className = 'custom-dropdown-menu absolute left-0 right-0 mt-1.5 bg-neutral-950 border border-neutral-900 rounded-xl shadow-2xl py-1.5 z-[99999] opacity-0 scale-95 pointer-events-none transition-all duration-150 origin-top max-h-60 overflow-y-auto scrollbar-thin';
                        
                        Array.from(select.options).forEach((opt, idx) => {
                            const item = document.createElement('div');
                            item.className = 'custom-dropdown-item px-4 py-2 text-xs sm:text-sm text-neutral-400 hover:bg-neutral-900 hover:text-white cursor-pointer transition-colors font-medium select-none truncate';
                            if (opt.selected) item.className += ' bg-neutral-900 text-white font-semibold';
                            if (opt.disabled) item.className += ' opacity-40 cursor-not-allowed pointer-events-none';
                            
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
                            if (isOpen) closeMenu(); else openMenu();
                        });

                        document.addEventListener('click', closeMenu);

                        select.parentNode.insertBefore(wrapper, select);
                        wrapper.appendChild(select);
                        wrapper.appendChild(toggleBtn);
                        wrapper.appendChild(menu);
                    });
                };

                window.initCustomDropdowns();
                setInterval(window.initCustomDropdowns, 800);
            });
        </script>
        @livewireScripts
        @stack('scripts')
    </body>
</html>
