<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.vouchers.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Create New Voucher') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Voucher Code</label>
                            <input type="text" name="code" value="{{ old('code') }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all uppercase" 
                                placeholder="E.G. PROMO2024" required>
                            @error('code') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Discount Type</label>
                            <select name="type" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Reward Amount</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="reward_amount" value="{{ old('reward_amount') }}" 
                                    class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                    placeholder="0" required>
                            </div>
                            @error('reward_amount') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Usage Limit</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                placeholder="Unlimited if empty">
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300">Expiration Date</label>
                            <input type="date" name="expires_at" value="{{ old('expires_at') }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex justify-end gap-4">
                        <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary px-8">
                            Create Voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
