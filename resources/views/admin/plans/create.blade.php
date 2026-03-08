<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.plans.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ isset($plan) ? __('Edit Plan') : __('Create New Plan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <form action="{{ isset($plan) ? route('admin.plans.update', $plan) : route('admin.plans.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(isset($plan)) @method('PUT') @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Plan Name</label>
                            <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                placeholder="E.G. Starter Pack" required>
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Technology Stack</label>
                            <select name="type" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="PHP" {{ old('type', $plan->type ?? '') == 'PHP' ? 'selected' : '' }}>PHP Hosting</option>
                                <option value="NodeJS" {{ old('type', $plan->type ?? '') == 'NodeJS' ? 'selected' : '' }}>NodeJS Hosting</option>
                                <option value="Fullstack" {{ old('type', $plan->type ?? '') == 'Fullstack' ? 'selected' : '' }}>Fullstack (PHP + Node)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Price (IDR)</label>
                            <input type="number" name="price" value="{{ old('price', $plan->price ?? '') }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                placeholder="E.G. 50000" required>
                            @error('price') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Duration (Months)</label>
                            <input type="number" name="duration_months" value="{{ old('duration_months', $plan->duration_months ?? 1) }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Max Storage (MB)</label>
                            <input type="number" name="max_storage_mb" value="{{ old('max_storage_mb', $plan->max_storage_mb ?? 1024) }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Max Databases</label>
                            <input type="number" name="max_databases" value="{{ old('max_databases', $plan->max_databases ?? 1) }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                placeholder="0 for no database" required>
                        </div>

                        <div class="flex items-center gap-3 pt-6">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                class="w-5 h-5 bg-gray-900 border-gray-800 rounded text-primary-500 focus:ring-primary-500 transition-all cursor-pointer"
                                {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                            <label for="is_active" class="text-sm font-medium text-gray-300 cursor-pointer">Active Status (Visible to Customers)</label>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" rows="3" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                placeholder="Optional plan description...">{{ old('description', $plan->description ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex justify-end gap-4">
                        <a href="{{ route('admin.plans.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary px-8">
                            {{ isset($plan) ? 'Update Plan' : 'Create Plan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
