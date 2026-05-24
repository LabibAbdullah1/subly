<footer class="bg-black border-t border-neutral-900 pt-16 pb-8 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-primary-600/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-12">
            
            <!-- Company Info -->
            <div class="space-y-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-neutral-950 border border-neutral-900 transition-all duration-300 group-hover:border-primary-500/40">
                        <img type="image/png" src="{{ asset('favicon-v2.png') }}" alt="Subly" class="w-6 h-6">
                    </div>
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-neutral-100 to-neutral-400">
                        {{ config('app.name', 'Subly') }}
                    </span>
                </a>
                <p class="text-neutral-400 text-xs sm:text-sm font-medium leading-relaxed max-w-xs">
                    Empowering your digital infrastructure with seamless deployment, scalable solutions, and premium support.
                </p>
                <div class="flex items-center space-x-4 pt-2">
                    <a href="https://www.tiktok.com/@subly.my.id" class="text-neutral-500 hover:text-primary-400 transition-colors">
                        <span class="sr-only">Tiktok</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.89-.6-4.13-1.47-.13 1.96-.22 3.91-.35 5.85-.18 2.03-.83 4.1-2.35 5.57-1.48 1.47-3.51 2.11-5.59 2.08-2.03-.02-4.13-.73-5.46-2.33-1.39-1.67-1.71-3.99-1.07-6.06.57-2.14 2.23-3.92 4.31-4.66.19-.07.4-.11.6-.17v4.03c-.27.08-.54.16-.8.29-1.01.52-1.74 1.61-1.74 2.75 0 1.29 1.05 2.34 2.34 2.34 1.29 0 2.34-1.04 2.34-2.34V0l.15.02z"></path></svg>
                    </a>
                    <a href="https://github.com/LabibAbdullah1" class="text-neutral-500 hover:text-white transition-colors">
                        <span class="sr-only">GitHub</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-xs font-bold text-neutral-400 tracking-widest mb-4 uppercase">Platform</h3>
                <ul class="space-y-3">
                    <li><a href="{{ url('/') }}#features" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Features</a></li>
                    <li><a href="{{ url('/') }}#pricing" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Pricing</a></li>
                    <li><a href="{{ route('login') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Client Portal</a></li>
                    <li><a href="{{ route('register') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Sign Up</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-xs font-bold text-neutral-400 tracking-widest mb-4 uppercase">Support</h3>
                <ul class="space-y-3">
                    <li><a href="https://portofolio-labib.vercel.app" target="_blank" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Developers</a></li>
                    <li><a href="{{ route('dashboard') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Live Chat</a></li>
                </ul>
            </div>

            <!-- Legal & Company -->
            <div>
                <h3 class="text-xs font-bold text-neutral-400 tracking-widest mb-4 uppercase">Legal</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('pages.terms') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Terms of Service</a></li>
                    <li><a href="{{ route('pages.purchase-terms') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Purchase Terms <span class="text-[10px] bg-red-500/10 border border-red-500/20 text-red-400 px-1.5 py-0.5 rounded-lg ml-1 font-bold">Important</span></a></li>
                    <li><a href="{{ route('pages.rules') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Application Rules</a></li>
                    <li><a href="{{ route('pages.privacy') }}" class="text-neutral-400 hover:text-white transition-colors text-xs sm:text-sm font-medium">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        
        <div class="pt-8 border-t border-neutral-900 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-neutral-500 text-xs sm:text-sm font-medium text-center md:text-left">
                &copy; {{ date('Y') }} {{ config('app.name', 'Subly') }} Inc. All rights reserved.
            </p>
            <div class="flex items-center gap-2 text-xs sm:text-sm text-neutral-400 font-semibold bg-neutral-900/40 border border-neutral-850 px-3.5 py-2 rounded-xl">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                All Systems Operational
            </div>
        </div>
    </div>
</footer>
