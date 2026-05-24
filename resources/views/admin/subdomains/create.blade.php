<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subdomains.index') }}" class="text-neutral-400 hover:text-white transition-all duration-200 active:scale-[0.94]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Register New Subdomain') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-3xl mx-auto select-none px-4 sm:px-0">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="mb-6">
                <h3 class="text-base font-bold text-white tracking-wide">Register Subdomain Host</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Specify target client, subscription packages, server document paths, and technology stack rules.</p>
            </div>

            <form action="{{ route('admin.subdomains.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <x-input-label for="user_id" :value="__('Owner (Client)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="user_id" id="user_id" class="input-field mt-1.5 block w-full text-xs" required>
                            <option value="" disabled selected>Select a client</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="plan_id" :value="__('Subscription Plan')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="plan_id" id="plan_id" class="input-field mt-1.5 block w-full text-xs" required>
                            <option value="" disabled selected>Select active plan package</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} - Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_months }} Months
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-neutral-550 font-bold uppercase mt-1">This plan starts active immediately upon subdomain provisioning.</p>
                        @error('plan_id') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="name" :value="__('Subdomain Name')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <div class="flex items-center gap-2 mt-1.5">
                            <x-text-input type="text" name="name" id="name" :value="old('name')" 
                                class="flex-1 block w-full font-mono text-xs" 
                                placeholder="my-cool-project" required />
                            <span class="text-neutral-500 font-mono text-xs font-semibold select-none">{{ config('app.subdomain_suffix') }}</span>
                        </div>
                        <p class="text-[10px] text-neutral-500 font-semibold italic mt-1.5">Directory root paths on cPanel will match this target domain prefix.</p>
                        @error('name') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="doc_root" :value="__('Document Root Folder (Folder Root Asli)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input type="text" name="doc_root" id="doc_root" :value="old('doc_root')" 
                            class="mt-1.5 block w-full font-mono text-xs" 
                            placeholder="/home/username/public_html/subdomain" />
                        <p class="text-[10px] text-neutral-500 font-semibold italic mt-1.5">Absolute document root folder path. If left empty, it will be automatically calculated on cPanel directory bindings.</p>
                        @error('doc_root') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="status" :value="__('Initial Status')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="status" id="status" class="input-field mt-1.5 block w-full text-xs" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="pt-5 border-t border-neutral-900/60 flex justify-end gap-3">
                    <x-secondary-button type="button" onclick="window.history.back()">
                        Cancel
                    </x-secondary-button>
                    <x-primary-button>
                        Create Subdomain
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const docRootInput = document.getElementById('doc_root');
            const prefix = "{{ config('app.doc_root_prefix') }}";
            
            if (nameInput && docRootInput) {
                let isManuallyEdited = false;
                
                docRootInput.addEventListener('input', function() {
                    isManuallyEdited = true;
                });
                
                nameInput.addEventListener('input', function() {
                    if (!isManuallyEdited) {
                        docRootInput.value = prefix + nameInput.value;
                    }
                });
            }
        });
    </script>
</x-admin-layout>
