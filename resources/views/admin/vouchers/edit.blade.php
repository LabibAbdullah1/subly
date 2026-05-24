<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vouchers.index') }}" class="text-neutral-400 hover:text-white transition-all duration-200 active:scale-[0.94]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
                {{ __('Edit Voucher') }}: <span class="text-white font-mono">{{ $voucher->code }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-3xl mx-auto select-none px-4 sm:px-0">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="mb-6">
                <h3 class="text-base font-bold text-white tracking-wide">Perbarui Voucher Promo</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Ubah kode diskon, persentase potongan, kuota penggunaan, dan parameter kedaluwarsa.</p>
            </div>

            <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="code" :value="__('Kode Voucher')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="code" type="text" name="code" :value="old('code', $voucher->code)" class="mt-1.5 block w-full uppercase font-mono text-xs" required />
                        @error('code') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="type" :value="__('Tipe Diskon')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <select name="type" id="type" class="input-field mt-1.5 block w-full text-xs" required>
                            <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Nominal Tetap (IDR)</option>
                            <option value="percent" {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="reward_amount" :value="__('Nilai Potongan')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="reward_amount" type="number" step="0.01" name="reward_amount" :value="old('reward_amount', $voucher->reward_amount)" class="mt-1.5 block w-full text-xs" required />
                        @error('reward_amount') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <x-input-label for="usage_limit" :value="__('Batas Kuota Penggunaan')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="usage_limit" type="number" name="usage_limit" :value="old('usage_limit', $voucher->usage_limit)" class="mt-1.5 block w-full text-xs" placeholder="Tak terbatas jika dikosongkan" />
                        @error('usage_limit') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="expires_at" :value="__('Tanggal Kedaluwarsa')" class="text-neutral-455 text-[11px] font-bold uppercase tracking-wider" />
                        <x-text-input id="expires_at" type="date" name="expires_at" :value="old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d') : '')" class="mt-1.5 block w-full text-xs cursor-pointer text-neutral-450" />
                        @error('expires_at') <span class="text-red-400 text-xs font-semibold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-5 border-t border-neutral-900/60 flex justify-end gap-3">
                    <x-secondary-button type="button" onclick="window.history.back()">
                        Batal
                    </x-secondary-button>
                    <x-primary-button>
                        Perbarui Voucher
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
