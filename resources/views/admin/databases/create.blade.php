<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Tetapkan Kredensial Database') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-3xl mx-auto select-none px-4 sm:px-0">
        <div class="glass-panel p-6 sm:p-8 border-neutral-900">
            <div class="mb-6">
                <h3 class="text-base font-bold text-white tracking-wide">Tambah Kredensial Baru</h3>
                <p class="text-xs text-neutral-500 font-medium mt-1">Tentukan rincian akses database untuk lingkungan subdomain klien.</p>
            </div>

            <form action="{{ route('admin.databases.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="subdomain_id" :value="__('Subdomain Target')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
                    <select name="subdomain_id" id="subdomain_id" class="input-field mt-1.5 block w-full" required>
                        <option value="">Pilih subdomain...</option>
                        @foreach($subdomains as $sub)
                            <option value="{{ $sub->id }}" {{ old('subdomain_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->full_domain }} ({{ $sub->user->name ?? 'Tidak Diketahui' }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('subdomain_id')" class="mt-2 text-xs font-semibold" />
                </div>

                <div>
                    <x-input-label for="db_name" :value="__('Nama Database')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
                    <x-text-input id="db_name" type="text" name="db_name" :value="old('db_name')" class="mt-1.5 block w-full" placeholder="contoh: subly_db_client" required />
                    <x-input-error :messages="$errors->get('db_name')" class="mt-2 text-xs font-semibold" />
                </div>

                <div>
                    <x-input-label for="db_user" :value="__('Username Database')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
                    <x-text-input id="db_user" type="text" name="db_user" :value="old('db_user')" class="mt-1.5 block w-full" placeholder="contoh: subly_user_client" required />
                    <x-input-error :messages="$errors->get('db_user')" class="mt-2 text-xs font-semibold" />
                </div>

                <div>
                    <x-input-label for="db_password" :value="__('Password Database')" class="text-neutral-450 text-[11px] font-bold uppercase tracking-wider" />
                    <x-text-input id="db_password" type="text" name="db_password" :value="old('db_password')" class="mt-1.5 block w-full" placeholder="contoh: string_kunci_acak_yang_aman" required />
                    <x-input-error :messages="$errors->get('db_password')" class="mt-2 text-xs font-semibold" />
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-900/60">
                    <x-secondary-button type="button" onclick="window.history.back()">
                        {{ __('Batal') }}
                    </x-secondary-button>
                    <x-primary-button>
                        {{ __('Tetapkan Database') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
