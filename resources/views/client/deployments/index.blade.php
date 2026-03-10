<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ __('Riwayat Deployment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel overflow-hidden">
                <div class="p-6 border-b border-gray-800 bg-gray-900/50">
                    <h3 class="text-lg font-medium text-gray-100">Seluruh Riwayat Deployment</h3>
                    <p class="text-sm text-gray-400 mt-1">Daftar rekaman seluruh aktivitas deployment pada semua subdomain Anda.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/80 border-b border-gray-800">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Subdomain</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal & Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($deployments as $deployment)
                                <tr class="hover:bg-gray-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-primary-400">{{ $deployment->subdomain->full_domain }}</span>
                                            <span class="text-[10px] text-gray-500 font-mono">ID: #{{ $deployment->id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-200">{{ $deployment->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $deployment->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                            {{ $deployment->status === 'success' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                            {{ $deployment->status === 'queued' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : '' }}
                                            {{ $deployment->status === 'processing' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                            {{ $deployment->status === 'error' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}">
                                            {{ ucfirst($deployment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('client.portal', $deployment->subdomain) }}" class="text-xs font-bold text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded-lg border border-gray-700 transition-all">
                                            Buka Portal
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-gray-400 font-medium">Belum ada riwayat deployment.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($deployments->hasPages())
                    <div class="p-6 border-t border-gray-800 bg-gray-900/30">
                        {{ $deployments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
