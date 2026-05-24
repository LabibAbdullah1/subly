<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            {{ __('Broadcast Notification') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-4xl mx-auto select-none px-4 sm:px-0 space-y-6">
        <!-- Form Card -->
        <div class="glass-panel overflow-hidden border-neutral-900" x-data="{ target: 'all', sending: false }">
            <div class="p-6 border-b border-neutral-900 bg-neutral-950/40">
                <h3 class="text-base font-bold text-white tracking-wide">Compose Client Announcement</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Broadcast high-priority alerts to the dashboard notifications box of target accounts.</p>
            </div>

            <form action="{{ route('admin.notifications.store') }}" method="POST" class="p-6 sm:p-8 space-y-5" @submit="sending = true">
                @csrf

                @if(session('success'))
                    <div class="p-4 rounded-xl bg-neutral-900 border border-neutral-850 flex items-center gap-3 animate-fade-in mb-4">
                        <div class="bg-neutral-950 p-2 rounded-lg text-neutral-350 border border-neutral-900">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white tracking-wide">Broadcast Sent</p>
                            <p class="text-[10px] text-neutral-500 font-semibold mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                    <div x-init="window.showToast('{{ session('success') }}', 'success')"></div>
                @endif

                <!-- Target Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-neutral-450 uppercase tracking-wider">Target Recipient</label>
                        <div class="flex gap-4 mt-1.5">
                            <label class="relative flex-1 flex items-center justify-center gap-3 cursor-pointer p-4 rounded-xl border transition-all duration-200 outline-none select-none active:scale-[0.98]"
                                :class="target === 'all' ? 'border-white bg-neutral-900/60' : 'border-neutral-900 bg-neutral-950 hover:border-neutral-800'">
                                <input type="radio" name="target" value="all" x-model="target" class="hidden">
                                <div class="w-4.5 h-4.5 rounded-full border transition-all flex items-center justify-center"
                                    :class="target === 'all' ? 'border-white bg-white' : 'border-neutral-700'">
                                    <div class="w-1.5 h-1.5 rounded-full bg-black" x-show="target === 'all'"></div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold transition-colors" :class="target === 'all' ? 'text-white' : 'text-neutral-450'">All Clients</span>
                                    <span class="text-[9px] text-neutral-500 font-semibold mt-0.5">Broadcast globally</span>
                                </div>
                            </label>

                            <label class="relative flex-1 flex items-center justify-center gap-3 cursor-pointer p-4 rounded-xl border transition-all duration-200 outline-none select-none active:scale-[0.98]"
                                :class="target === 'specific' ? 'border-white bg-neutral-900/60' : 'border-neutral-900 bg-neutral-950 hover:border-neutral-800'">
                                <input type="radio" name="target" value="specific" x-model="target" class="hidden">
                                <div class="w-4.5 h-4.5 rounded-full border transition-all flex items-center justify-center"
                                    :class="target === 'specific' ? 'border-white bg-white' : 'border-neutral-700'">
                                    <div class="w-1.5 h-1.5 rounded-full bg-black" x-show="target === 'specific'"></div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold transition-colors" :class="target === 'specific' ? 'text-white' : 'text-neutral-450'">Specific User</span>
                                    <span class="text-[9px] text-neutral-500 font-semibold mt-0.5">Target single client</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Specific User Dropdown -->
                    <div class="space-y-2" x-show="target === 'specific'" x-cloak x-transition>
                        <label for="user_id" class="block text-[11px] font-bold text-neutral-450 uppercase tracking-wider">Select Target User</label>
                        <select name="user_id" id="user_id" class="input-field mt-1.5 block w-full">
                            <option value="">Select a client...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-400 text-xs font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Message Description Text -->
                <div class="space-y-2">
                    <label for="message" class="block text-[11px] font-bold text-neutral-450 uppercase tracking-wider">Message Content</label>
                    <textarea name="message" id="message" rows="5" required
                        class="w-full bg-neutral-950 border border-neutral-900 rounded-xl px-4 py-3 text-xs text-neutral-200 focus:border-neutral-700 focus:ring-1 focus:ring-neutral-700 outline-none transition-all resize-none shadow-inner"
                        placeholder="Write the notification message details here..."></textarea>
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-[9px] text-neutral-500 font-semibold italic">Maximum 1000 characters limit.</p>
                        @error('message') <p class="text-red-400 text-xs font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Action Submit Button -->
                <div class="flex justify-end pt-4 border-t border-neutral-900/60">
                    <button type="submit" 
                        class="btn-primary px-6"
                        :disabled="sending">
                        <span x-show="!sending" class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                            Blast Notification
                        </span>
                        <span x-show="sending" class="flex items-center gap-1.5">
                            <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Sending...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips Info Box -->
        <div class="glass-panel p-5 border-neutral-900 border-l-2 border-l-white">
            <div class="flex items-start gap-4">
                <div class="bg-neutral-900 p-2.5 rounded-lg border border-neutral-850 text-neutral-450 shrink-0 select-none">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white tracking-wide">Usage Reference</h4>
                    <p class="text-[10px] text-neutral-500 font-semibold leading-relaxed mt-1">
                        Use broadcast notifications to alert clients regarding planned database/server maintenance schedules, announcements of upcoming billing changes, or direct responses to urgent help desk tickets. Sent items propagate instantly to targeted environments.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-admin-layout>
