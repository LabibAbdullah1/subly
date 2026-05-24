@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-xs text-green-400 flex items-center gap-2 bg-green-500/10 px-3.5 py-2.5 rounded-xl border border-green-500/20 font-semibold shadow-[0_0_15px_rgba(34,197,94,0.05)]']) }}>
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ $status }}</span>
    </div>
@endif
