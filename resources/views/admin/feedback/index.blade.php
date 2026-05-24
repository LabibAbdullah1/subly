<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase">
            {{ __('Client Feedback Moderation') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 max-w-7xl mx-auto space-y-6 select-none px-4 sm:px-0">
        
        <!-- Welcome Title -->
        <div class="flex flex-col gap-1.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Feedback Directory</h1>
            <p class="text-xs text-neutral-500 font-medium">Moderate client evaluations, star ratings, and toggle testimonial highlights.</p>
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
                            <th class="table-th text-[10px]">Client</th>
                            <th class="table-th text-[10px]">Plan</th>
                            <th class="table-th text-[10px]">Rating</th>
                            <th class="table-th text-[10px]">Comment</th>
                            <th class="table-th text-center text-[10px]">Featured</th>
                            <th class="table-th text-right text-[10px] pr-8">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-900/40">
                        @forelse($feedbacks as $feedback)
                            <tr class="group hover:bg-neutral-900/20 transition-all duration-300">
                                <td class="table-td">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-full bg-neutral-900 border border-neutral-850 flex items-center justify-center text-[10px] font-bold text-neutral-300 uppercase">
                                            {{ substr($feedback->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-neutral-200 group-hover:text-white transition-colors">{{ $feedback->user->name }}</span>
                                    </div>
                                </td>
                                <td class="table-td">
                                    @if($feedback->plan)
                                        <span class="px-2 py-0.5 inline-flex text-[9px] leading-5 font-bold uppercase tracking-wider rounded-md bg-neutral-900 border border-neutral-850 text-neutral-300">
                                            {{ $feedback->plan->name }}
                                        </span>
                                    @else
                                        <span class="text-neutral-500 font-semibold italic text-[10px] tracking-wide">General</span>
                                    @endif
                                </td>
                                <td class="table-td">
                                    <div class="flex text-white">
                                        @for($i = 0; $i < 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i < $feedback->rating ? 'fill-current text-white' : 'text-neutral-750' }}" fill="{{ $i < $feedback->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </td>
                                <td class="table-td text-xs text-neutral-400 max-w-xs truncate">{{ $feedback->comment }}</td>
                                <td class="table-td text-center">
                                    <form action="{{ route('admin.feedback.toggle_featured', $feedback) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold tracking-wider uppercase transition-all duration-200 outline-none select-none cursor-pointer border active:scale-[0.96]
                                            {{ $feedback->is_featured ? 'bg-white text-black border-transparent' : 'bg-neutral-950 text-neutral-500 border-neutral-900 hover:border-neutral-800' }}">
                                            <svg class="w-3 h-3" fill="{{ $feedback->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                            </svg>
                                            {{ $feedback->is_featured ? 'Featured' : 'Highlight' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="table-td text-right pr-8">
                                    <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" onsubmit="confirm('Delete this user testimonial permanently?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-neutral-600 hover:text-red-400 font-bold text-xs transition-colors cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-td text-center py-12 text-xs text-neutral-500 font-semibold italic">No feedback entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($feedbacks->hasPages())
                <div class="p-4 border-t border-neutral-900 bg-neutral-950/40">{{ $feedbacks->links() }}</div>
            @endif
        </div>
    </div>
</x-admin-layout>
