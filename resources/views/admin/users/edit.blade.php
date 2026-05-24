<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Edit User') }}: <span class="text-primary-400">{{ $user->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                required>
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                                required>
                            @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-300">Role</label>
                            <select name="role" class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" required>
                                <option value="Client" {{ old('role', $user->role) == 'Client' ? 'selected' : '' }}>Client</option>
                                <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="space-y-2 pt-4 border-t border-gray-800">
                            <label class="block text-sm font-medium text-gray-300">Change Password (Leave blank to keep current)</label>
                            <input type="password" name="password" 
                                class="w-full bg-gray-900 border border-gray-800 rounded-lg py-2.5 px-4 text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex justify-end gap-4">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary px-8">
                            Update User Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
