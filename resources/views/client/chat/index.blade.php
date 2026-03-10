<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            {{ __('Live Chat Support') }}
        </h2>
    </x-slot>

    <div class="py-12 h-[calc(100vh-65px)] px-4">
        <div class="max-w-4xl mx-auto h-full pb-10">
            <div id="support" class="glass-panel p-6 flex flex-col h-full" x-data="clientChat()">
                <div class="flex justify-between items-center mb-6 border-b border-gray-800/50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-indigo-600 flex items-center justify-center shadow-[0_0_15px_rgba(94,106,210,0.4)]">
                            <span class="text-white font-bold text-sm">CS</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-100">Customer Support</h3>
                            <p class="text-xs text-gray-400 flex items-center gap-1.5 mt-0.5">
                                <span class="relative flex h-2 w-2" x-show="adminOnline">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span class="h-2 w-2 rounded-full bg-gray-600" x-show="!adminOnline"></span>
                                <span x-text="adminOnline ? 'Online. Reply instantly.' : 'Offline. We\'ll be back soon.'"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Messages Box -->
                <div class="flex-1 overflow-y-auto mb-4 space-y-3 px-2 py-4 rounded-xl bg-gray-900/30 border border-gray-800/50 shadow-inner" id="clientMessagesBox">
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex items-start gap-2.5 group w-full" :class="!msg.is_admin ? 'justify-end' : ''">
                            <div x-show="msg.is_admin" class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5 bg-gradient-to-br from-primary-500 to-indigo-600 shadow-[0_0_10px_rgba(94,106,210,0.3)]">
                                CS
                            </div>
                            <div class="flex flex-col max-w-[75%]" :class="!msg.is_admin ? 'items-end' : 'items-start'">
                                <div class="px-5 py-3 rounded-2xl text-[14px] shadow-md leading-relaxed"
                                        :class="!msg.is_admin ? 'bg-primary-600 text-white rounded-tr-sm' : 'bg-gray-800 text-gray-200 rounded-tl-sm border border-gray-700/50'">
                                    <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                </div>
                                <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-1.5 px-1">
                                    <span x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                    <button @click="deleteMessage(msg.id)" x-show="!msg.is_admin" class="text-red-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity font-medium">
                                        Delete
                                    </button>
                                    <svg x-show="!msg.is_admin && msg.is_read" class="w-3.5 h-3.5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="messages.length === 0" class="h-full flex flex-col items-center justify-center text-gray-500 italic text-sm py-10 w-full">
                        <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <p class="text-gray-400 font-medium">No messages yet.</p>
                        <p class="text-xs mt-1 text-center">We typically reply in a few minutes.<br>Send a message to start conversing!</p>
                    </div>
                </div>

                <!-- Input Area -->
                <form @submit.prevent="sendMessage" class="flex gap-3 mt-auto bg-gray-900/50 p-2 rounded-xl border border-gray-800/50">
                    <div class="relative flex-1 group">
                        <input type="text" x-model="newMessage" placeholder="Type your message..." class="input-field w-full bg-transparent border-none focus:ring-0 text-base py-3" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn-primary py-3 px-6 shadow-[0_0_15px_rgba(94,106,210,0.3)] transition-all hover:scale-[1.02] flex items-center justify-center gap-2 rounded-lg" :disabled="sending">
                        <span x-show="!sending" class="font-medium hidden sm:inline">Send</span>
                        <svg x-show="!sending" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <svg x-show="sending" class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('clientChat', () => ({
                messages: [],
                newMessage: '',
                sending: false,
                pollingInterval: null,
                adminOnline: false,

                init() {
                    this.fetchMessages();
                    this.pollingInterval = setInterval(() => {
                        this.fetchMessages();
                    }, 3000); // Poll every 3 seconds for new messages and read receipts
                },

                destroy() {
                    if (this.pollingInterval) clearInterval(this.pollingInterval);
                },

                async fetchMessages() {
                    try {
                        const res = await fetch(`{{ route('client.chat.messages') }}`);
                        if(res.ok) {
                            const data = await res.json();
                            const newMessages = data.messages;
                            this.adminOnline = data.admin_online;
                            
                            if(JSON.stringify(this.messages) !== JSON.stringify(newMessages)) {
                                this.messages = newMessages;
                                this.$nextTick(() => this.scrollToBottom());
                            }
                        }
                    } catch(e) { console.error('Error fetching chat:', e); }
                },

                async sendMessage() {
                    if(!this.newMessage.trim()) return;
                    this.sending = true;
                    try {
                        const res = await fetch(`{{ route('client.chat.store') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.content || document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1],
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: this.newMessage })
                        });
                        if(res.ok) {
                            this.newMessage = '';
                            await this.fetchMessages();
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    } catch(e) { console.error('Send error:', e); }
                    this.sending = false;
                },

                async deleteMessage(id) {
                    if(!confirm('Delete this message permanently?')) return;
                    try {
                        const res = await fetch(`/dashboard/chat/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.content || document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1],
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
                    const box = document.getElementById('clientMessagesBox');
                    if(box) box.scrollTop = box.scrollHeight;
                }
            }));
        });
    </script>
</x-app-layout>
