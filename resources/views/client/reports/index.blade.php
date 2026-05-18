<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            {{ __('Pusat Dukungan / Tiket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3 animate-fade-in shadow-lg" role="alert">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Buka Tiket Baru -->
            <div class="glass-panel p-6 sm:p-8 relative overflow-hidden border border-primary-500/20 shadow-[0_0_30px_rgba(94,106,210,0.1)]">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl pointer-events-none"></div>
                
                <h3 class="text-xl font-bold text-gray-100 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Buka Tiket Baru
                </h3>
                <p class="text-gray-400 text-sm mb-6">Laporkan kendala teknis atau pertanyaan seputar layanan hosting Anda kepada tim dukungan kami.</p>

                <form action="{{ route('client.reports.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">Subjek Masalah</label>
                        <input type="text" name="subject" id="subject" class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-gray-100 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-all text-sm font-medium" placeholder="Contoh: Database error / Subdomain tidak dapat diakses" required>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Detail Kronologi & Pesan</label>
                        <textarea name="message" id="message" rows="4" class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-gray-100 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-all text-sm font-medium leading-relaxed" placeholder="Jelaskan kendala Anda secara rinci untuk mempermudah tim kami melakukan pengecekan..." required></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary py-3 px-8 shadow-[0_0_20px_rgba(94,106,210,0.3)] font-bold text-sm flex items-center gap-2 group hover:scale-[1.02] transition-transform">
                            Kirim Tiket
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Tiket -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Riwayat Tiket Dukungan Anda
                </h3>

                <div class="glass-panel overflow-hidden border-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="table-th">Subjek & Pesan</th>
                                    <th class="table-th text-right w-36">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800/50">
                                @forelse($reports as $report)
                                    <tr class="hover:bg-gray-800/20 transition-colors">
                                        <td class="table-td py-5">
                                            <div class="font-bold text-gray-200 text-base mb-1.5 flex items-center gap-2">
                                                @if($report->status === 'open')
                                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse shrink-0"></span>
                                                @elseif($report->status === 'in_progress')
                                                    <span class="w-2 h-2 rounded-full bg-yellow-500 shrink-0"></span>
                                                @else
                                                    <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                                                @endif
                                                {{ $report->subject }}
                                            </div>
                                            <div class="text-gray-400 text-sm bg-gray-900/40 p-4 rounded-xl border border-gray-800/50 mb-3 whitespace-pre-wrap leading-relaxed">
                                                {{ $report->message }}
                                            </div>
                                            <div class="text-gray-500 text-xs font-mono flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ $report->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td class="table-td py-5 text-right align-top">
                                            <span class="inline-block font-semibold px-3 py-1 rounded-full text-xs uppercase tracking-wider border
                                                @if($report->status === 'open') bg-red-500/10 text-red-400 border-red-500/20
                                                @elseif($report->status === 'in_progress') bg-yellow-500/10 text-yellow-400 border-yellow-500/20
                                                @else bg-green-500/10 text-green-400 border-green-500/20
                                                @endif">
                                                {{ str_replace('_', ' ', $report->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="table-td text-center py-16">
                                            <div class="flex flex-col items-center justify-center text-gray-500 space-y-3">
                                                <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center border border-gray-800">
                                                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                                </div>
                                                <p class="text-sm font-medium text-gray-400">Belum ada riwayat tiket dukungan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($reports->hasPages())
                        <div class="p-4 border-t border-gray-800/50 bg-gray-900/30">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
