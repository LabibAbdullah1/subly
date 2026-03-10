<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subly') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-300 antialiased bg-gray-950 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden selection:bg-primary-500/30 selection:text-primary-100">
        
        <!-- Ambient Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[20%] w-[40%] h-[40%] rounded-full bg-primary-900/10 blur-[120px]"></div>
            <div class="absolute bottom-[20%] right-[-10%] w-[30%] h-[30%] rounded-full bg-indigo-900/10 blur-[100px]"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMiIvPjwvc3ZnPg==')] opacity-20"></div>
        </div>

        <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-gray-900/60 backdrop-blur-xl border border-gray-800 shadow-2xl sm:rounded-2xl">
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center shadow-[0_0_15px_rgba(94,106,210,0.5)] group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 9h19M2.5 15h19M12 2c3 0 6 4.477 6 10s-3 10-6 10-6-4.477-6-10 3-10 6-10z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400 tracking-tight">Subly</span>
                </a>
            </div>

            {{ $slot }}
        </div>
    </body>
</html>
