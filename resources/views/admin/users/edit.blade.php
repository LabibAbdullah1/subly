<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-neutral-400 hover:text-white transition-all duration-200 active:scale-[0.94]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Edit User') }}: <span class="text-white font-mono">{{ $user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-3xl mx-auto select-none px-4 sm:px-0">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="mb-6">
                <h3 class="text-base font-bold text-white tracking-wide">Update Client Account</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Modify user details, update access roles, or change account passwords.</p>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <x-input-label for="name" :value="__('Full Name')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="name" type="text" name="name" :value="old('name', $user->name)" class="mt-1.5 block w-full" placeholder="e.g. John Doe" required />
                        @error('name') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="email" type="email" name="email" :value="old('email', $user->email)" class="mt-1.5 block w-full" placeholder="john@example.com" required />
                        @error('email') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role Access')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="role" id="role" class="input-field mt-1.5 block w-full text-xs" required>
                            <option value="Client" {{ old('role', $user->role) == 'Client' ? 'selected' : '' }}>Client</option>
                            <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Change Password (Leave blank to keep current)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="password" type="password" name="password" class="mt-1.5 block w-full" />
                        @error('password') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-5 border-t border-neutral-900/60 flex justify-end gap-3">
                    <x-secondary-button type="button" onclick="window.history.back()">
                        Cancel
                    </x-secondary-button>
                    <x-primary-button>
                        Update User Data
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
