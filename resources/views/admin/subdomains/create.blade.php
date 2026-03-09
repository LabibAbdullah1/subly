<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subdomains.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Register New Subdomain') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <form action="{{ route('admin.subdomains.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Owner (Client)</label>
                            <select name="user_id" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="" disabled selected>Select a client</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Plan</label>
                            <select name="plan_id" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="" disabled selected>Select a plan for user</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - Rp {{ number_format($plan->price, 0, ',', '.') }} / {{ $plan->duration_months }} Months
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 italic">This plan will be assigned automatically to the client as active.</p>
                            @error('plan_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Subdomain Name</label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="name" value="{{ old('name') }}" 
                                    class="flex-1 bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono" 
                                    placeholder="my-cool-project" required>
                                <span class="text-gray-500 font-mono">{{ config('app.subdomain_suffix') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 italic">This will be used for both the URL and the directory name.</p>
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Initial Status</label>
                            <select name="status" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex justify-end gap-4">
                        <a href="{{ route('admin.subdomains.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary px-8">
                            Create Subdomain
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
