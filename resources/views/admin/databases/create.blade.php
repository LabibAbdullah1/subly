<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Assign Database Credentials') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel p-8">
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-100">Add New Credentials</h3>
                    <p class="text-sm text-gray-400 mt-1">Specify database access details for a client subdomain.</p>
                </div>

                <form action="{{ route('admin.databases.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="subdomain_id" :value="__('Target Subdomain')" class="text-gray-300" />
                        <select name="subdomain_id" id="subdomain_id" class="input-field mt-1 block w-full appearance-none pr-10" required>
                            <option value="">Select a subdomain...</option>
                            @foreach($subdomains as $sub)
                                <option value="{{ $sub->id }}" class="bg-gray-900 text-gray-100" {{ old('subdomain_id') == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->full_domain }} ({{ $sub->user->name ?? 'Unknown' }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('subdomain_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="db_name" :value="__('Database Name')" class="text-gray-300" />
                        <x-text-input id="db_name" type="text" name="db_name" :value="old('db_name')" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('db_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="db_user" :value="__('Database Username')" class="text-gray-300" />
                        <x-text-input id="db_user" type="text" name="db_user" :value="old('db_user')" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('db_user')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="db_password" :value="__('Database Password')" class="text-gray-300" />
                        <x-text-input id="db_password" type="text" name="db_password" :value="old('db_password')" class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('db_password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <x-secondary-button type="button" onclick="window.history.back()">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Save Credentials') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
