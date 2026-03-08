<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.subdomains.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Edit Subdomain') }}: <span class="text-primary-400">{{ $subdomain->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <form action="{{ route('admin.subdomains.update', $subdomain) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Owner (Client)</label>
                            <select name="user_id" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (old('user_id', $subdomain->user_id) == $user->id) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Subdomain Name</label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="name" value="{{ old('name', $subdomain->name) }}" 
                                    class="flex-1 bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono" 
                                    placeholder="my-cool-project" required>
                                <span class="text-gray-500 font-mono">.subly.test</span>
                            </div>
                            <p class="text-xs text-gray-500 italic">Changing this will update the URL and directory name. Use with caution for active projects.</p>
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Status</label>
                            <select name="status" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="active" {{ old('status', $subdomain->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $subdomain->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex justify-end gap-4">
                        <a href="{{ route('admin.subdomains.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary px-8">
                            Update Subdomain
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Quick Info -->
            <div class="mt-8 glass-panel p-6 border-l-4 border-primary-500 shadow-xl">
                 <h4 class="text-gray-100 font-medium mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Technical Details
                 </h4>
                 <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block">Current Doc Root:</span>
                        <code class="text-gray-300 bg-gray-950 px-2 py-1 rounded border border-gray-800">{{ $subdomain->doc_root }}</code>
                    </div>
                    <div>
                        <span class="text-gray-500 block">External URL:</span>
                        <a href="http://{{ $subdomain->full_domain }}" target="_blank" class="text-primary-400 hover:underline">http://{{ $subdomain->full_domain }}</a>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>
