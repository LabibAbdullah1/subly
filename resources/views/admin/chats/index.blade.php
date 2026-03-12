<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Live Chat Support') }}
        </h2>
    </x-slot>

    <div class="py-2 sm:py-4" x-data="adminChat()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex h-[80vh] sm:h-[75vh] min-h-[500px] glass-panel overflow-hidden" style="height: 75vh;">
                <!-- User List -->
                <div class="w-full sm:w-1/3 border-r border-gray-800 flex flex-col h-full bg-gray-900/50 transition-all duration-300"
                     :class="currentUserId ? 'hidden sm:flex' : 'flex'">
                    <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-100">Clients</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        @forelse($users as $user)
                            <button @click="selectUser({{ $user->id }}, '{{ $user->name }}')" 
                                    class="w-full text-left p-4 border-b border-gray-800/50 hover:bg-gray-800 transition-colors flex justify-between items-center"
                                    :class="{'bg-gray-800/80': currentUserId === {{ $user->id }}}">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-200">{{ $user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                                @if($user->unread_count > 0)
                                    <span class="bg-primary-500 text-white text-xs font-bold px-2 py-0.5 rounded-full" x-show="currentUserId !== {{ $user->id }}">{{ $user->unread_count }}</span>
                                @endif
                            </button>
                        @empty
                            <div class="p-4 text-center text-gray-500 text-sm">No clients have messages yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="w-full sm:w-2/3 flex flex-col h-full relative bg-gray-900/40" x-show="currentUserId" style="display: none;">
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-800 bg-gray-900/80 shrink-0 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Back Button for Mobile -->
                            <button @click="currentUserId = null" class="sm:hidden p-1.5 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <h3 class="text-lg font-medium text-gray-100" x-text="currentUserName"></h3>
                        </div>
                    </div>

                    <!-- Messages Box -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="adminMessagesBox">
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex items-start gap-3 group" :class="msg.is_admin ? 'flex-row-reverse' : ''">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                     :class="msg.is_admin ? 'bg-primary-600' : 'bg-gray-600'">
                                    <span x-text="msg.is_admin ? 'A' : currentUserName.substring(0,1)"></span>
                                </div>
                                <div class="flex flex-col" :class="msg.is_admin ? 'items-end' : 'items-start'">
                                    <div class="px-4 py-2 rounded-2xl max-w-[85%] sm:max-w-sm text-sm shadow-md"
                                         :class="msg.is_admin ? 'bg-primary-600 text-white rounded-tr-none' : 'bg-gray-800 text-gray-200 rounded-tl-none'">
                                        <p x-text="msg.message" class="whitespace-pre-wrap text-[13px] sm:text-sm"></p>
                                    </div>
                                    <div class="text-[10px] text-gray-500 mt-1 flex items-center gap-2">
                                        <span x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                        <button @click="deleteMessage(msg.id)" class="text-red-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="messages.length === 0" class="h-full flex items-center justify-center text-gray-500 italic text-sm">No messages yet.</div>
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 border-t border-gray-800 bg-gray-900/80 shrink-0">
                        <form @submit.prevent="sendMessage" class="flex gap-2">
                            <input type="text" x-model="newMessage" placeholder="Type a message..." class="input-field flex-1 py-2 sm:py-3 text-sm sm:text-base" required autocomplete="off">
                            <button type="submit" class="btn-primary py-2 px-4 sm:px-6" :disabled="sending">
                                <span x-show="!sending" class="hidden sm:inline">Send</span>
                                <svg x-show="!sending" class="sm:hidden w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                <span x-show="sending">...</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Empty State (Desktop only) -->
                <div class="hidden sm:flex w-2/3 items-center justify-center h-full text-gray-500" x-show="!currentUserId">
                    Select a client to start chatting
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminChat', () => ({
                currentUserId: null,
                currentUserName: '',
                messages: [],
                newMessage: '',
                sending: false,
                pollingInterval: null,

                selectUser(id, name) {
                    this.currentUserId = id;
                    this.currentUserName = name;
                    this.fetchMessages();
                    
                    if (this.pollingInterval) clearInterval(this.pollingInterval);
                    this.pollingInterval = setInterval(() => {
                        this.fetchMessages(true);
                    }, 3000); // Poll every 3 seconds
                },

                async fetchMessages(isPolling = false) {
                    if (!this.currentUserId) return;
                    try {
                        const res = await fetch(`/admin/chat/${this.currentUserId}`);
                        if(res.ok) {
                            const newMessages = await res.json();
                            if(JSON.stringify(this.messages) !== JSON.stringify(newMessages)) {
                                this.messages = newMessages;
                                this.$nextTick(() => this.scrollToBottom());
                            }
                        }
                    } catch(e) { console.error('Error fetching chat:', e); }
                },

                async sendMessage() {
                    if(!this.newMessage.trim() || !this.currentUserId) return;
                    this.sending = true;
                    try {
                        const res = await fetch(`/admin/chat/${this.currentUserId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: this.newMessage })
                        });
                        if(res.ok) {
                            this.newMessage = '';
                            await this.fetchMessages();
                        }
                    } catch(e) { console.error('Send error:', e); }
                    this.sending = false;
                },

                async deleteMessage(id) {
                    if(!confirm('Delete this message permanently?')) return;
                    try {
                        const res = await fetch(`/admin/chat/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if(res.ok) {
                            this.messages = this.messages.filter(m => m.id !== id);
                            if(window.showToast) window.showToast('Message deleted');
                        }
                    } catch(e) { console.error('Delete error:', e); }
                },

                scrollToBottom() {
                    const box = document.getElementById('adminMessagesBox');
                    if(box) box.scrollTop = box.scrollHeight;
                }
            }));
        });
    </script>
    @endpush
</x-admin-layout>
