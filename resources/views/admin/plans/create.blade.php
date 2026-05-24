<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.plans.index') }}" class="text-neutral-400 hover:text-white transition-all duration-200 active:scale-[0.94]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
                {{ isset($plan) ? __('Edit Paket') : __('Buat Paket Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-3xl mx-auto select-none px-4 sm:px-0">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="mb-6">
                <h3 class="text-base font-bold text-white tracking-wide">{{ isset($plan) ? 'Perbarui Paket Hosting' : 'Buat Paket Hosting' }}</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Konfigurasi batasan lingkungan dan model harga langganan.</p>
            </div>

            <form action="{{ isset($plan) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST" class="space-y-5">
                @csrf
                @if(isset($plan)) @method('PUT') @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="name" :value="__('Nama Paket')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="name" type="text" name="name" :value="old('name', $plan->name ?? '')" class="mt-1.5 block w-full" placeholder="contoh: Developer Pack" required />
                        @error('name') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="type" :value="__('Teknologi Stack')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="type" id="type" class="input-field mt-1.5 block w-full" required>
                            @foreach(\App\Models\Plan::TYPES as $value => $info)
                                <option value="{{ $value }}" {{ old('type', $plan->type ?? '') == $value ? 'selected' : '' }}>{{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="price" :value="__('Harga (IDR)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="price" type="number" name="price" :value="old('price', $plan->price ?? '')" class="mt-1.5 block w-full" placeholder="contoh: 50000" required />
                        @error('price') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="duration_months" :value="__('Durasi (Bulan)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="duration_months" type="number" name="duration_months" :value="old('duration_months', $plan->duration_months ?? 1)" class="mt-1.5 block w-full" required />
                        @error('duration_months') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="max_storage_mb" :value="__('Penyimpanan Maks (MB)')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="max_storage_mb" type="number" name="max_storage_mb" :value="old('max_storage_mb', $plan->max_storage_mb ?? 1024)" class="mt-1.5 block w-full" required />
                        @error('max_storage_mb') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="max_databases" :value="__('Database Maks')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="max_databases" type="number" name="max_databases" :value="old('max_databases', $plan->max_databases ?? 1)" class="mt-1.5 block w-full" required />
                        @error('max_databases') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-4 md:col-span-2 select-none">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                            class="w-5 h-5 rounded border border-neutral-800 bg-black text-white focus:ring-neutral-700 transition-all cursor-pointer"
                            {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-xs font-semibold text-neutral-300 cursor-pointer">Status Aktif (Terlihat di pilihan paket checkout)</label>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <x-input-label for="description" :value="__('Deskripsi')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <textarea name="description" id="description" rows="3" 
                            class="w-full bg-neutral-950 border border-neutral-900 rounded-xl px-4 py-3 text-xs text-neutral-200 focus:border-neutral-700 focus:ring-1 focus:ring-neutral-700 outline-none transition-all resize-none shadow-inner" 
                            placeholder="Deskripsi detail paket opsional (terlihat oleh klien)...">{{ old('description', $plan->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="pt-5 border-t border-neutral-900/60 flex justify-end gap-3">
                    <x-secondary-button type="button" onclick="window.history.back()">
                        Batal
                    </x-secondary-button>
                    <x-primary-button>
                        {{ isset($plan) ? 'Perbarui Paket' : 'Buat Paket' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
