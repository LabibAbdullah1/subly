<x-guest-layout>
    <div class="mb-6 text-xs font-semibold text-neutral-400 leading-relaxed bg-black/60 border border-neutral-900 rounded-xl p-4 select-none">
        {{ __('Terima kasih sudah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan? Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-neutral-950 border border-neutral-900 text-white rounded-xl px-4 py-3 text-xs font-semibold tracking-wide shadow-md" role="alert">
            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 select-none">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <x-primary-button class="h-12 uppercase font-extrabold text-xs tracking-wider whitespace-nowrap">
                {{ __('Kirim Ulang Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto text-center">
            @csrf
            <button type="submit" class="text-xs font-bold uppercase tracking-wider text-neutral-500 hover:text-white transition-colors cursor-pointer">
                {{ __('Keluar') }}
            </button>
        </form>
    </div>
</x-guest-layout>
