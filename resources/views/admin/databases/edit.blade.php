<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Edit Database Credentials') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-100">Update Credentials</h3>
                    <p class="text-sm text-gray-400 mt-1">Modify database access details for {{ $database->subdomain->full_domain }}.</p>
                </div>

                <form action="{{ route('admin.databases.update', $database) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="subdomain_id" :value="__('Target Subdomain')" class="text-gray-300" />
                        <select name="subdomain_id" id="subdomain_id" class="input-field mt-1 block w-full appearance-none pr-10" required>
                            @foreach($subdomains as $sub)
                                <option value="{{ $sub->id }}" class="bg-gray-900 text-gray-100" {{ (old('subdomain_id', $database->subdomain_id) == $sub->id) ? 'selected' : '' }}>
                                    {{ $sub->full_domain }} ({{ $sub->user->name ?? 'Unknown' }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subdomain_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="db_name" :value="__('Database Name')" class="text-gray-300" />
                        <x-text-input id="db_name" type="text" name="db_name" :value="old('db_name', $database->db_name)" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('db_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="db_user" :value="__('Database Username')" class="text-gray-300" />
                        <x-text-input id="db_user" type="text" name="db_user" :value="old('db_user', $database->db_user)" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('db_user')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="db_password" :value="__('Database Password')" class="text-gray-300" />
                        <x-text-input id="db_password" type="text" name="db_password" :value="old('db_password', $database->db_password)" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('db_password')" class="mt-2" />
                        <p class="mt-1 text-xs text-gray-500">Note: Password was hidden for security but can be overwritten here.</p>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <x-secondary-button type="button" onclick="window.history.back()">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Update Credentials') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
