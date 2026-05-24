<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
            </svg>
            {{ __('Direktori Subdomain') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Direktori Subdomain</h1>
                <p class="text-xs text-neutral-500 font-medium mt-1">Kelola dan audit situs web klien aktif, periksa jadwal kadaluarsa, dan moderasi jalur live.</p>
            </div>
            <a href="{{ route('admin.subdomains.create') }}" class="btn-primary w-full sm:w-auto text-center active:scale-[0.98]">
                Daftarkan Subdomain Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-neutral-900/50 border border-neutral-850 text-neutral-200 p-4 rounded-xl flex items-center gap-3 shadow-lg" role="alert">
                <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
            </div>
        @endif

        <div class="glass-panel overflow-hidden border-neutral-900">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="table-th text-[10px]">Pemilik</th>
                            <th class="table-th text-[10px]">Subdomain</th>
                            <th class="table-th text-[10px]">URL Lengkap</th>
                            <th class="table-th text-[10px]">Sisa Waktu</th>
                            <th class="table-th text-[10px]">Status</th>
                            <th class="table-th text-right text-[10px] pr-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($subdomains as $sub)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td">
                                    <div class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $sub->user->name ?? 'Tidak Diketahui' }}</div>
                                    <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">{{ $sub->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="table-td font-mono text-xs text-neutral-350">{{ $sub->name }}</td>
                                <td class="table-td">
                                    <a href="https://{{ $sub->full_domain }}" target="_blank" class="text-xs text-neutral-450 hover:text-white transition-colors flex items-center gap-1">
                                        {{ $sub->full_domain }}
                                        <svg class="w-3 h-3 text-neutral-600 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                </td>
                                <td class="table-td">
                                    @if($sub->expired_at)
                                        <div class="text-xs font-bold {{ $sub->expired_at->isPast() ? 'text-red-400 font-semibold' : 'text-neutral-250' }}">
                                            {{ $sub->expired_at->diffForHumans() }}
                                        </div>
                                        <div class="text-[10px] text-neutral-500 font-semibold mt-0.5">{{ $sub->expired_at->format('d M Y') }}</div>
                                    @else
                                        <span class="text-neutral-500 font-semibold italic text-xs">Seumur Hidup/Tidak Ada</span>
                                    @endif
                                </td>
                                <td class="table-td">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase tracking-wider rounded-md border 
                                        {{ $sub->status === 'active' ? 'bg-neutral-900/40 text-neutral-300 border-neutral-850' : 'bg-red-950/20 text-red-400 border-red-900/10' }}">
                                        {{ $sub->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td class="table-td text-right pr-8">
                                    <div class="flex items-center justify-end gap-3.5 select-none">
                                        <a href="{{ route('admin.subdomains.edit', $sub) }}" class="text-neutral-500 hover:text-white transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.subdomains.destroy', $sub) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus subdomain ini? Ini akan menghapus semua lingkungan database dan deployment yang terkait secara permanen.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-neutral-600 hover:text-red-400 transition-colors cursor-pointer" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">Tidak ada subdomain ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($subdomains->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">{{ $subdomains->links() }}</div>
            @endif
        </div>
    </div>
</x-admin-layout>
