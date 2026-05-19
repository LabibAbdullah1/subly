<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subly') }} | Managed Hosting</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon-v2.png') }}">

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
            @include('layouts.navigation')
            
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 xl:hidden" style="display: none;"></div>

            <!-- Page Content Area -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10 w-full backdrop-blur-[2px]">
                
                <!-- Top Header -->
                <header class="h-16 border-b border-gray-800 bg-gray-900/30 backdrop-blur-md flex items-center justify-between px-4 sm:px-6 shrink-0 relative z-20">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="xl:hidden text-gray-400 hover:text-white focus:outline-none p-2 mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <!-- Breadcrumb/Title placeholder -->
                        @isset($header)
                            <div class="hidden xl:block text-sm font-medium text-gray-400">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Notification Bell (Moved from navbar) -->
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
                                    <a href="{{ route('client.notifications.index') }}" class="text-sm font-medium text-gray-200 hover:text-primary-400 transition-colors flex items-center gap-1">
                                        Notifications
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                        <div class="px-4 py-3 border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors {{ is_null($notification->read_at) ? 'bg-gray-800/10' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <p class="text-sm text-gray-300 leading-snug">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                            </div>
                                            <span class="text-xs text-gray-500 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">No new notifications.</div>
                                    @endforelse
                                </div>
                            </x-slot>
                        </x-dropdown>

                        <!-- User Profile (Moved from navbar) -->
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

                @isset($header)
                    <div class="px-4 sm:px-6 lg:px-8 py-5 border-b border-gray-800/60 bg-transparent flex flex-col gap-2 shrink-0 xl:hidden">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto w-full relative animate-fade-in p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Toast Container -->
        <div id="toast-container" class="fixed top-20 right-6 z-[100] flex flex-col gap-3 pointer-events-none"></div>

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
                @apply bg-gray-900/60 backdrop-blur-xl border border-white/10 text-gray-100 pr-6 pl-9 py-4 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex flex-row items-center gap-5 whitespace-nowrap pointer-events-auto relative overflow-hidden transition-all duration-300;
                animation: toast-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .toast-progress {
                @apply absolute bottom-0 left-0 h-1 bg-primary-500/50;
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
        </style>

        <script>
            window.showToast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast border-l-4 ${type === 'success' ? 'border-l-primary-500' : 'border-l-red-500'}`;
                
                const icon = type === 'success' 
                    ? '<svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    : '<svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';

                toast.innerHTML = `
                    <div class="absolute inset-0 bg-gradient-to-r ${type === 'success' ? 'from-primary-500/5' : 'from-red-500/5'} to-transparent opacity-50 pointer-events-none"></div>
                    ${icon} 
                    <span class="text-sm font-semibold tracking-wide relative z-10">${message}</span>
                    <div class="toast-progress ${type === 'success' ? 'bg-primary-500' : 'bg-red-500'}"></div>
                `;
                container.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('toast-out');
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            };

            // Intercept standard browser confirm() dialogs in the capture phase for forms and links
            document.addEventListener('DOMContentLoaded', function() {
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
                    container.className = 'relative bg-[#09090b]/98 border border-gray-800 rounded-2xl max-w-md w-full p-6 mx-4 shadow-[0_25px_60px_rgba(0,0,0,0.95),_0_0_50px_rgba(239,68,68,0.08)] transform scale-95 transition-all duration-300 flex flex-col overflow-hidden z-10';
                    
                    // Ambient glows
                    const accentGlow = isDelete 
                        ? '<div class="absolute -right-16 -top-16 w-36 h-36 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>'
                        : '<div class="absolute -right-16 -top-16 w-36 h-36 bg-primary-600/10 rounded-full blur-3xl pointer-events-none"></div>';

                    // Select styles based on theme/action
                    const iconContainerClass = isDelete 
                        ? 'text-red-500 bg-red-500/10 border-red-500/20' 
                        : 'text-primary-400 bg-primary-500/10 border-primary-500/20';

                    const confirmButtonClass = isDelete
                        ? 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white shadow-[0_0_15px_rgba(239,68,68,0.3)] hover:shadow-[0_0_20px_rgba(239,68,68,0.5)] active:scale-[0.98]'
                        : 'bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-500 hover:to-primary-400 text-white shadow-[0_0_15px_rgba(94,106,210,0.3)] hover:shadow-[0_0_20px_rgba(94,106,210,0.5)] active:scale-[0.98]';

                    const iconSvg = isDelete
                        ? `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`
                        : `<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;

                    container.innerHTML = `
                        ${accentGlow}
                        <div class="flex items-center gap-4 mb-4 relative z-10">
                            <div class="p-3 rounded-xl border ${iconContainerClass} flex-shrink-0 animate-pulse">
                                ${iconSvg}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white tracking-wide">${title}</h3>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Konfirmasi Tindakan</p>
                            </div>
                        </div>
                        <div class="mb-6 text-sm text-gray-300 leading-relaxed relative z-10">
                            ${message}
                        </div>
                        <div class="flex items-center justify-end gap-3 relative z-10">
                            <button type="button" class="btn-cancel px-4 py-2.5 rounded-xl border border-gray-800 text-gray-400 hover:text-white hover:bg-gray-800/50 transition-all duration-200 text-sm font-bold">
                                Batal
                            </button>
                            <button type="button" class="btn-confirm px-5 py-2.5 rounded-xl ${confirmButtonClass} transition-all duration-200 text-sm font-bold">
                                Konfirmasi
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
                        let message = "Apakah Anda yakin ingin melanjutkan?";
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

                        let titleText = "Konfirmasi Tindakan";
                        if (onsubmitAttr.toLowerCase().includes('berhenti') || onsubmitAttr.toLowerCase().includes('langganan')) {
                            titleText = "Berhenti Berlangganan";
                        } else if (isDeleteAction) {
                            titleText = "Hapus Data Permanen";
                        }

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

                            let message = "Apakah Anda yakin?";
                            const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                            if (match && match[1]) {
                                message = match[1];
                            }

                            window.showCustomConfirmModal({
                                title: 'Konfirmasi Tindakan',
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
            });
        </script>
        @stack('scripts')
    </body>
</html>
