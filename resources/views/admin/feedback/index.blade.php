<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Client Feedback moderation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="glass-panel overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="table-th">Client</th>
                                <th class="table-th">Plan</th>
                                <th class="table-th">Rating</th>
                                <th class="table-th">Comment</th>
                                <th class="table-th text-center">Featured</th>
                                <th class="table-th text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800/50">
                            @forelse($feedbacks as $feedback)
                                <tr class="group hover:bg-gray-800/30 transition-colors">
                                    <td class="table-td">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-600 flex items-center justify-center text-xs font-bold text-white uppercase">
                                                {{ substr($feedback->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-gray-100 font-medium">{{ $feedback->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        @if($feedback->plan)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-md bg-primary-500/10 text-primary-400 border border-primary-500/20">
                                                {{ $feedback->plan->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 italic text-xs">General</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex text-yellow-500">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 {{ $i < $feedback->rating ? 'fill-current' : 'text-gray-600' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="table-td text-gray-400 max-w-md truncate">{{ $feedback->comment }}</td>
                                    <td class="table-td text-center">
                                        <form action="{{ route('admin.feedback.toggle_featured', $feedback) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold transition-all {{ $feedback->is_featured ? 'bg-primary-500/20 text-primary-400 border border-primary-500/30' : 'bg-gray-800 text-gray-500 border border-gray-700' }}">
                                                <svg class="w-3 h-3" fill="{{ $feedback->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                                {{ $feedback->is_featured ? 'Featured' : 'Mark Featured' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="table-td text-right">
                                        <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" onsubmit="return confirm('Delete this feedback?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="table-td text-center py-12 text-gray-500">No feedback submitted yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-gray-800/60 bg-gray-900/30">{{ $feedbacks->links() }}</div>
            </div>
        </div>
    </div>
</x-admin-layout>
