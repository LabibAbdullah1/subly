<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-sm text-neutral-450 tracking-wider uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            {{ __('Live Chat Support') }}
        </h2>
    </x-slot>

    <div class="py-4 select-none" x-data="adminChat()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex h-[78vh] sm:h-[75vh] min-h-[500px] glass-panel overflow-hidden border-neutral-900">
                <!-- User List Panel -->
                <div class="w-full sm:w-1/3 border-r border-neutral-900 flex flex-col h-full bg-neutral-950/40 transition-all duration-300"
                     :class="currentUserId ? 'hidden sm:flex' : 'flex'">
                    <div class="p-5 border-b border-neutral-900 flex items-center justify-between shrink-0">
                        <div class="flex flex-col gap-0.5">
                            <h3 class="text-sm font-semibold text-white">Active Channels</h3>
                            <p class="text-[10px] text-neutral-500 font-medium">Select a client to respond.</p>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto divide-y divide-neutral-900/40 scrollbar-thin">
                        @forelse($users as $user)
                            <button @click="selectUser({{ $user->id }}, '{{ $user->name }}')" 
                                    class="w-full text-left p-5 hover:bg-neutral-900/20 transition-all flex justify-between items-center outline-none active:scale-[0.99]"
                                    :class="{'bg-neutral-900/40 border-l-2 border-l-white': currentUserId === {{ $user->id }}}">
                                <div class="truncate mr-3">
                                    <h4 class="text-xs font-bold text-neutral-200 transition-colors group-hover:text-white" :class="{'text-white': currentUserId === {{ $user->id }}}">{{ $user->name }}</h4>
                                    <p class="text-[10px] text-neutral-500 font-medium truncate mt-0.5">{{ $user->email }}</p>
                                </div>
                                @if($user->unread_count > 0)
                                    <span class="bg-white text-black text-[9px] font-bold px-2 py-0.5 rounded-md shrink-0 tracking-wider shadow" x-show="currentUserId !== {{ $user->id }}">{{ $user->unread_count }}</span>
                                @endif
                            </button>
                        @empty
                            <div class="p-8 text-center text-neutral-500 text-xs font-medium">No active chat channels.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Area Panel -->
                <div class="w-full sm:w-2/3 flex flex-col h-full relative bg-neutral-950/20" x-show="currentUserId" style="display: none;">
                    <!-- Chat Header -->
                    <div class="p-5 border-b border-neutral-900 bg-neutral-950/50 backdrop-blur-md shrink-0 flex items-center justify-between z-10">
                        <div class="flex items-center gap-3.5">
                            <!-- Back Button for Mobile -->
                            <button @click="currentUserId = null" class="sm:hidden p-1.5 text-neutral-400 hover:text-white transition-colors hover:bg-neutral-900 rounded-lg active:scale-[0.94]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-neutral-900 border border-neutral-850 flex items-center justify-center text-xs font-bold text-neutral-300 uppercase select-none" x-text="currentUserName.substring(0,1)"></div>
                                <div>
                                    <h3 class="text-xs font-bold text-white tracking-wide" x-text="currentUserName"></h3>
                                    <p class="text-[9px] text-neutral-500 font-bold uppercase tracking-wider mt-0.5">Support Session</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Box -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-5 scrollbar-thin bg-black/10" id="adminMessagesBox">
                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex items-start gap-3.5 group" :class="msg.is_admin ? 'flex-row-reverse' : ''">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0 border select-none"
                                     :class="msg.is_admin ? 'bg-white text-black border-transparent font-extrabold' : 'bg-neutral-900 text-neutral-400 border-neutral-850'">
                                    <span x-text="msg.is_admin ? 'A' : currentUserName.substring(0,1)"></span>
                                </div>
                                <div class="flex flex-col max-w-[75%]" :class="msg.is_admin ? 'items-end' : 'items-start'">
                                    <!-- Image Attachment -->
                                    <template x-if="msg.image_path">
                                        <div class="p-1 rounded-xl shadow bg-neutral-950 border border-neutral-900 mb-1.5 hover:border-neutral-800 transition-colors">
                                            <img :src="'{{ asset('storage') }}/' + msg.image_path" class="max-w-full h-auto rounded-lg max-h-60 object-contain cursor-pointer transition-all hover:opacity-90 active:scale-[0.99]" @click="window.open('{{ asset('storage') }}/' + msg.image_path, '_blank')">
                                        </div>
                                    </template>
                                    <!-- Text Bubble -->
                                    <div class="px-4 py-2.5 rounded-xl text-xs shadow-sm leading-relaxed" x-show="msg.message"
                                         :class="msg.is_admin ? 'bg-white text-black font-semibold rounded-tr-none' : 'bg-neutral-900 text-neutral-200 border border-neutral-850/60 rounded-tl-none'">
                                        <p x-text="msg.message" class="whitespace-pre-wrap tracking-wide"></p>
                                    </div>
                                    <!-- Time & Delete Action Row -->
                                    <div class="text-[9px] text-neutral-500 font-semibold mt-1.5 flex items-center gap-2">
                                        <span x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                        <button @click="removeChatMessage(msg.id)" class="text-neutral-600 hover:text-red-400 transition-colors p-1 rounded hover:bg-neutral-900/50 outline-none" title="Delete message">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="messages.length === 0" class="h-full flex flex-col items-center justify-center text-neutral-500 italic text-xs font-semibold py-20 select-none">
                            No chat logs recorded yet.
                        </div>
                    </div>

                    <!-- Input Console Area -->
                    <div class="border-t border-neutral-900 bg-neutral-950/40 shrink-0 relative z-10">
                        <!-- Image Upload Preview Banner -->
                        <div x-show="imagePreview" class="px-5 py-2.5 bg-neutral-950 border-b border-neutral-900 flex items-center gap-3.5">
                            <div class="relative w-10 h-10 bg-neutral-900 rounded-lg overflow-hidden border border-neutral-800">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] text-neutral-450 truncate flex-1 font-mono font-bold" x-text="selectedFile ? selectedFile.name : ''"></p>
                            <button @click="clearFile" class="text-red-400 p-1 hover:bg-red-500/10 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        
                        <form @submit.prevent="sendMessage" class="p-4 flex gap-3 items-center">
                            <!-- Attachment Trigger -->
                            <button type="button" @click="$refs.fileInput.click()" class="p-2.5 text-neutral-400 hover:text-white rounded-xl hover:bg-neutral-900/50 transition-all duration-200 active:scale-[0.95]" title="Upload file attachment">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                            <input type="file" x-ref="fileInput" @change="onFileSelected" class="hidden" accept="image/*">
                            
                            <!-- Messages Input Box -->
                            <input type="text" x-model="newMessage" placeholder="Type a response message..." class="input-field flex-1 py-2 text-xs bg-neutral-950 hover:bg-neutral-900/40 placeholder-neutral-500 focus:border-neutral-700 focus:ring-neutral-700 rounded-xl" autocomplete="off">
                            
                            <!-- Send Button -->
                            <button type="submit" class="btn-primary px-5 py-2.5 shrink-0" :disabled="sending || (newMessage.trim().length === 0 && !selectedFile)">
                                <span x-show="!sending" class="hidden sm:inline">Send</span>
                                <svg x-show="!sending" class="sm:hidden w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                <svg x-show="sending" class="animate-spin h-4 w-4 text-black" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Chat Empty State Panel (Desktop only) -->
                <div class="hidden sm:flex w-2/3 items-center justify-center h-full text-neutral-500 text-xs font-semibold py-20 select-none bg-neutral-950/10" x-show="!currentUserId">
                    Select a support channel client to respond.
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
