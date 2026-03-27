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
                                    <template x-if="msg.image_path">
                                        <div class="p-1 rounded-2xl shadow-md overflow-hidden bg-white/5 border border-white/10 mb-1">
                                            <img :src="'/storage/' + msg.image_path" class="max-w-full h-auto rounded-xl max-h-60 object-contain cursor-pointer" @click="window.open('/storage/' + msg.image_path, '_blank')">
                                        </div>
                                    </template>
                                    <div class="px-4 py-2 rounded-2xl max-w-[85%] sm:max-w-sm text-sm shadow-md" x-show="msg.message"
                                         :class="msg.is_admin ? 'bg-primary-600 text-white rounded-tr-none' : 'bg-gray-800 text-gray-200 rounded-tl-none'">
                                        <p x-text="msg.message" class="whitespace-pre-wrap text-[13px] sm:text-sm"></p>
                                    </div>
                                    <div class="text-[10px] text-gray-500 mt-1 flex items-center gap-2">
                                        <span x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                        <button @click="removeChatMessage(msg.id)" class="text-gray-500 hover:text-red-500 transition-colors p-2 -m-1 rounded-full hover:bg-red-500/10" title="Delete message">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="messages.length === 0" class="h-full flex items-center justify-center text-gray-500 italic text-sm">No messages yet.</div>
                    </div>

                    <!-- Input Area -->
                    <div class="border-t border-gray-800 bg-gray-900/80 shrink-0">
                        <div x-show="imagePreview" class="px-4 py-2 bg-gray-900 border-b border-gray-800 flex items-center gap-3">
                            <div class="relative w-10 h-10 bg-gray-800 rounded overflow-hidden border border-gray-700">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[11px] text-gray-400 truncate flex-1" x-text="selectedFile ? selectedFile.name : ''"></p>
                            <button @click="clearFile" class="text-red-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        <form @submit.prevent="sendMessage" class="p-4 flex gap-2 items-center">
                            <button type="button" @click="$refs.fileInput.click()" class="p-2 text-gray-400 hover:text-primary-400 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                            <input type="file" x-ref="fileInput" @change="onFileSelected" class="hidden" accept="image/*">
                            
                            <input type="text" x-model="newMessage" placeholder="Type a message..." class="input-field flex-1 py-2 sm:py-3 text-sm sm:text-base bg-transparent border-none focus:ring-0" autocomplete="off">
                            <button type="submit" class="btn-primary py-2 px-4 sm:px-6" :disabled="sending || (newMessage.trim().length === 0 && !selectedFile)">
                                <span x-show="!sending" class="hidden sm:inline">Send</span>
                                <svg x-show="!sending" class="sm:hidden w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                <svg x-show="sending" class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
                messages: [],
                deletingIds: new Set(),
                isProcessing: false,
                currentUserId: null,
                currentUserName: '',
                pollingInterval: null,
                sending: false,
                newMessage: '',
                selectedFile: null,
                imagePreview: null,

                init() {
                    // Polling is started only when a user is selected
                },

                destroy() {
                    if (this.pollingInterval) clearInterval(this.pollingInterval);
                },

                selectUser(id, name) {
                    this.currentUserId = id;
                    this.currentUserName = name;
                    this.messages = [];
                    this.deletingIds.clear();
                    this.fetchMessages();
                    
                    if (this.pollingInterval) clearInterval(this.pollingInterval);
                    this.pollingInterval = setInterval(() => {
                        this.fetchMessages(true);
                    }, 3000);
                },

                onFileSelected(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (!file.type.startsWith('image/')) {
                        alert('Please select an image file.');
                        return;
                    }
                    this.selectedFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => this.imagePreview = e.target.result;
                    reader.readAsDataURL(file);
                },

                clearFile() {
                    this.selectedFile = null;
                    this.imagePreview = null;
                    this.$refs.fileInput.value = '';
                },

                async fetchMessages(isPolling = false) {
                    if (!this.currentUserId || (this.isProcessing && isPolling)) return;
                    try {
                        const res = await fetch(`{{ url('admin/chat') }}/${this.currentUserId}`);
                        if(res.ok) {
                            const data = await res.json();
                            const msgs = data || [];
                            const newMessages = msgs.filter(m => !this.deletingIds.has(m.id));
                            if(JSON.stringify(this.messages) !== JSON.stringify(newMessages)) {
                                this.messages = newMessages;
                                this.$nextTick(() => this.scrollToBottom());
                            }
                        }
                    } catch(e) { console.error('Error fetching chat:', e); }
                },

                async sendMessage() {
                    if((!this.newMessage.trim() && !this.selectedFile) || !this.currentUserId || this.sending) return;
                    this.sending = true;
                    this.isProcessing = true;

                    const formData = new FormData();
                    formData.append('message', this.newMessage);
                    if (this.selectedFile) {
                        formData.append('image', this.selectedFile);
                    }

                    try {
                        const res = await fetch(`{{ url('admin/chat') }}/${this.currentUserId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        if(res.ok) {
                            this.newMessage = '';
                            this.clearFile();
                            await this.fetchMessages();
                        }
                    } catch(e) { console.error('Send error:', e); }
                    this.sending = false;
                    this.isProcessing = false;
                },

                async removeChatMessage(id) {
                    if(!confirm('Delete this message permanently?')) return;
                    
                    this.deletingIds.add(id);
                    this.messages = this.messages.filter(m => m.id !== id);
                    this.isProcessing = true;
                    
                    try {
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        formData.append('_token', document.head.querySelector('meta[name="csrf-token"]')?.content || '');

                        const res = await fetch(`{{ url('admin/chat') }}/${id}`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' },
                            body: formData
                        });
                        
                        if(!res.ok) {
                            const data = await res.json();
                            alert('Failed to delete: ' + (data.error || 'Unknown error'));
                            this.deletingIds.delete(id);
                            await this.fetchMessages();
                        }
                    } catch(e) { 
                        console.error('Delete error:', e); 
                        alert('Connection error: ' + e.message);
                        this.deletingIds.delete(id);
                    } finally {
                        this.isProcessing = false;
                    }
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
