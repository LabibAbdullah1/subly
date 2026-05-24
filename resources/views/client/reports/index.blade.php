<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
            </svg>
            Tiket Dukungan
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10 select-none">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('success'))
                <div class="bg-neutral-950 border border-neutral-900 text-white px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in shadow-xl" role="alert">
                    <div class="p-1 rounded-lg bg-white/5 border border-white/10 text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <p class="text-xs font-semibold tracking-wide">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Open Ticket Form -->
            <div class="bg-neutral-950/40 border border-neutral-900 rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-2xl">
                <div class="absolute -right-24 -top-24 w-52 h-52 bg-white/2 rounded-full blur-3xl pointer-events-none"></div>
                
                <h3 class="text-sm font-bold text-white mb-1.5 flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buka Tiket Baru
                </h3>
                <p class="text-neutral-450 text-xs font-medium mb-6">Laporkan kendala teknis atau pertanyaan seputar layanan hosting Anda kepada tim dukungan kami.</p>

                <form action="{{ route('client.reports.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="subject" class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2 pl-0.5">Subjek Masalah</label>
                        <input type="text" name="subject" id="subject" class="input-field text-xs sm:text-sm font-semibold mt-1" placeholder="Contoh: Database error / Subdomain tidak dapat diakses" required>
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold text-neutral-450 uppercase tracking-widest mb-2 pl-0.5">Detail Kronologi & Pesan</label>
                        <textarea name="message" id="message" rows="4" class="input-field placeholder-neutral-600 resize-none font-semibold text-xs sm:text-sm mt-1 leading-relaxed" placeholder="Jelaskan kendala Anda secara rinci untuk mempermudah tim kami melakukan pengecekan..." required></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary py-3 px-8 h-12 shadow-[0_4px_16px_rgba(255,255,255,0.06)] font-extrabold text-xs uppercase tracking-wider flex items-center gap-1.5 active:scale-[0.98] cursor-pointer group">
                            Kirim Tiket
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- support Tickets list Table -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-neutral-455 uppercase tracking-widest flex items-center gap-2 pl-0.5">
                    <svg class="w-4 h-4 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    Riwayat Tiket Dukungan Anda
                </h3>

                <div class="bg-neutral-950/40 backdrop-blur-md border border-neutral-900 rounded-2xl overflow-hidden shadow-2xl">
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-neutral-950/80">
                                    <th class="table-th">Subjek & Pesan</th>
                                    <th class="table-th text-right w-36">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-900/50">
                                @forelse($reports as $report)
                                    <tr class="group hover:bg-neutral-900/10 transition-colors">
                                        <td class="table-td py-5">
                                            <div class="font-bold text-white text-sm mb-2 flex items-center gap-2.5">
                                                @if($report->status === 'open')
                                                    <span class="relative flex h-2 w-2 shrink-0">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                    </span>
                                                @elseif($report->status === 'in_progress')
                                                    <span class="w-2 h-2 rounded-full bg-yellow-500 shrink-0"></span>
                                                @else
                                                    <span class="w-2 h-2 rounded-full bg-neutral-600 shrink-0"></span>
                                                @endif
                                                {{ $report->subject }}
                                            </div>
                                            <div class="text-neutral-350 text-xs sm:text-sm bg-black/60 p-4 rounded-xl border border-neutral-900 mb-3.5 whitespace-pre-wrap leading-relaxed font-medium">
                                                {{ $report->message }}
                                            </div>
                                            <div class="text-neutral-550 text-[10px] font-mono font-medium flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-neutral-550" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                                {{ $report->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td class="table-td py-5 text-right align-top">
                                            <span class="px-2.5 py-0.5 inline-flex text-[9px] font-bold uppercase tracking-wider rounded-md border
                                                @if($report->status === 'open') bg-red-950/20 text-red-400 border-red-900/30
                                                @elseif($report->status === 'in_progress') bg-neutral-900 border-neutral-850 text-white
                                                @else bg-neutral-900/40 border-neutral-900 text-neutral-550
                                                @endif">
                                                {{ $report->status === 'open' ? 'Terbuka' : ($report->status === 'in_progress' ? 'Sedang Diproses' : ($report->status === 'resolved' ? 'Selesai' : $report->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-10 h-10 text-neutral-550 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                                </svg>
                                                <p class="text-neutral-550 text-xs font-bold uppercase tracking-widest">Belum ada riwayat tiket dukungan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($reports->hasPages())
                        <div class="p-6 border-t border-neutral-900 bg-neutral-950/20">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
