@if (!env('VITE_USE_CDN', false) && (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <!-- Standalone Tailwind CSS Play CDN Fallback for Zero-Build/cPanel Deployments -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        heading: ['Outfit', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        neutral: {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            250: '#dddddd',
                            300: '#d4d4d4',
                            350: '#bcbcbc',
                            400: '#a3a3a3',
                            450: '#888888',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            850: '#1a1a1a',
                            900: '#121212',
                            950: '#080808',
                        },
                        primary: {
                            400: '#7885e7',
                            500: '#5e6ad2',
                            600: '#4b55c3',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Axios CDN Fallback for Zero-Build/cPanel Deployments -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        
        // Add CSRF token to Axios headers if meta tag is present
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
        }
    </script>

    <!-- Custom CSS Compilation Fallback using Tailwind Directive -->
    <style type="text/tailwindcss">
        @layer components {
            /* Premium Inline SVG Dot Matrix Pattern */
            .bg-dot-grid {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Ccircle cx='12' cy='12' r='1' fill='%23fff' fill-opacity='0.17'/%3E%3C/svg%3E");
                background-repeat: repeat;
            }

            /* Premium High-Fidelity Dithering Noise to eliminate color banding in dark gradients */
            .bg-noise {
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 250 250' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            }

            /* Premium Glassmorphic Card inspired by Linear & Vercel */
            .glass-panel {
                @apply bg-neutral-950/60 backdrop-blur-xl border border-neutral-900 shadow-[0_24px_50px_-12px_rgba(0,0,0,0.85),inset_0_1px_0_0_rgba(255,255,255,0.045)] rounded-2xl transition-all duration-300;
            }

            /* Glowing card border masking effect - robust background border-gradient to avoid Blink/WebKit mask composite hover bugs */
            .glass-panel-glow {
                border: 1px solid transparent !important;
                background-image: linear-gradient(rgba(8, 8, 8, 0.6), rgba(8, 8, 8, 0.6)),
                                  linear-gradient(to bottom right, rgba(94,106,210,0.15), rgba(94,106,210,0.02) 45%, rgba(94,106,210,0.02) 55%, rgba(94,106,210,0.05)) !important;
                background-origin: border-box !important;
                background-clip: padding-box, border-box !important;
            }

            /* Elegant button system with click scaling */
            .btn-primary {
                @apply inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-primary-500 to-indigo-600 text-white border border-transparent rounded-xl font-heading font-semibold text-xs uppercase tracking-wider hover:from-primary-400 hover:to-indigo-500 hover:shadow-[0_0_25px_rgba(94,106,210,0.35)] focus:outline-none focus:ring-1 focus:ring-primary-500 transition-all duration-200 active:scale-[0.97] cursor-pointer;
            }

            .btn-secondary {
                @apply inline-flex items-center justify-center px-5 py-2.5 bg-neutral-950 border border-neutral-900 rounded-xl font-heading font-semibold text-xs uppercase tracking-wider text-neutral-350 hover:bg-neutral-900 hover:text-white hover:border-neutral-850 hover:shadow-[inset_0_1px_1px_rgba(255,255,255,0.05),0_0_20px_rgba(0,0,0,0.4)] focus:outline-none focus:ring-1 focus:ring-neutral-700 transition-all duration-200 active:scale-[0.97] cursor-pointer;
            }

            .btn-danger {
                @apply inline-flex items-center justify-center px-5 py-2.5 bg-red-950/20 text-red-400 border border-red-900/30 rounded-xl font-heading font-semibold text-xs uppercase tracking-wider hover:bg-red-900 hover:text-white focus:outline-none focus:ring-1 focus:ring-red-500 transition-all duration-200 active:scale-[0.97] cursor-pointer;
            }

            /* Optical alignment for button SVGs (especially with uppercase text) */
            .btn-primary svg, .btn-secondary svg, .btn-danger svg {
                @apply -translate-y-[1px] shrink-0;
            }

            /* Input Fields */
            .input-field {
                @apply mt-1 block w-full rounded-xl bg-black border border-neutral-900 text-neutral-200 px-4 py-2.5 placeholder-neutral-600 focus:border-primary-500/80 focus:ring-1 focus:ring-primary-500/25 sm:text-xs font-semibold tracking-wide transition-all duration-200 outline-none;
            }

            /* Premium Data Tables */
            .table-container {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-th {
                @apply px-6 py-4 text-left text-[10px] font-bold text-neutral-500 uppercase tracking-widest border-b border-neutral-900 bg-neutral-950/40 font-heading;
            }

            .table-td {
                @apply px-6 py-4 whitespace-nowrap text-xs text-neutral-300 border-b border-neutral-900/50 group-hover:bg-neutral-900/10 transition-colors font-medium;
            }

            /* Sidebar Link Styles */
            .sidebar-link {
                @apply flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-200 border border-transparent select-none font-heading;
            }
            .sidebar-link:not(.active) {
                @apply text-neutral-500 hover:text-neutral-200 hover:bg-neutral-900/20 hover:border-neutral-900/40;
            }
            .sidebar-link.active {
                @apply bg-neutral-950 text-white border-neutral-900 shadow-[inset_0_1px_0_rgba(255,255,255,0.05),0_8px_20px_-6px_rgba(0,0,0,0.8)] relative;
            }
            .sidebar-link.active::after {
                content: '';
                @apply absolute right-2 w-1.5 h-1.5 rounded-full bg-primary-500 shadow-[0_0_8px_rgba(94,106,210,0.8)];
            }

            /* Hover Lift for interactive components */
            .hover-lift {
                @apply transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(0,0,0,0.7),inset_0_1px_0_rgba(255,255,255,0.08)];
            }
        }

        /* Fluid Typography & Body */
        html {
            font-size: 14px;
        }
        @media (min-width: 640px) {
            html {
                font-size: 15px;
            }
        }
        @media (min-width: 1024px) {
            html {
                font-size: 16px;
            }
        }
        body {
            @apply bg-black text-neutral-350 antialiased font-sans selection:bg-neutral-200 selection:text-black;
            letter-spacing: -0.015em;
        }
        h1, h2, h3, h4, h5, h6 {
            @apply font-heading text-white font-bold;
            letter-spacing: -0.025em;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            @apply bg-black;
        }
        ::-webkit-scrollbar-thumb {
            @apply bg-neutral-900 rounded-full border border-black;
        }
        ::-webkit-scrollbar-thumb:hover {
            @apply bg-neutral-800;
        }
        input::placeholder, textarea::placeholder {
            font-size: 11px !important;
            font-weight: 500;
            letter-spacing: -0.015em;
        }
        @media (min-width: 640px) {
            input::placeholder, textarea::placeholder {
                font-size: 12px !important;
            }
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        @media (max-width: 640px) {
            input, select, textarea, .custom-dropdown-toggle {
                font-size: 16px !important;
            }
        }
    </style>
@endif
