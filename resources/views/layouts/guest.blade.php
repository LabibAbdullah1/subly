<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="background-color: #000000;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Subly') }} - Autentikasi</title>
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
    <body class="font-sans text-neutral-200 antialiased bg-black min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden selection:bg-white/20 selection:text-white" style="background-color: #000000;">
        
        <!-- Ambient Grid & Glow Canvas Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden select-none bg-black">
            <!-- Fine SVG Dot Matrix Pattern -->
            <div class="absolute inset-0 bg-dot-grid [mask-image:radial-gradient(ellipse_100%_80%_at_50%_50%,#000_80%,transparent_100%)] opacity-90"></div>
            
            <!-- Soft Violet & Purple Glows -->
            <div class="absolute top-[-15%] left-[-10%] w-[50%] h-[50%] rounded-full bg-primary-500/18 blur-[140px]"></div>
            <div class="absolute bottom-[-15%] right-[-10%] w-[50%] h-[50%] rounded-full bg-purple-500/14 blur-[140px]"></div>
        </div>

        <!-- Authentication Card Container -->
        <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 glass-panel glass-panel-glow border-neutral-900/60 shadow-[0_24px_50px_-12px_rgba(0,0,0,0.85)] sm:rounded-2xl">
            <!-- Brand Logo Mark -->
            <div class="flex justify-center mb-8 select-none">
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center transition-all group-hover:scale-102">
                        <img class="w-5.5 h-5.5 object-contain" src="{{ asset('favicon-v2.png') }}" alt="Subly">
                    </div>
                    <span class="font-bold text-base tracking-tight text-white transition-colors">Subly</span>
                </a>
            </div>

            <!-- Page Slots -->
            {{ $slot }}
        </div>
        @livewireScripts
    </body>
</html>
