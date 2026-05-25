<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xs text-neutral-450 uppercase tracking-widest flex items-center gap-2">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l3.755-4.133a.805.805 0 01.62-.27c1.152-.086 2.293-.213 3.423-.379 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>
            {{ __('Live Chat') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 h-[calc(100vh-100px)] select-none">
        <div class="max-w-4xl mx-auto h-full pb-8">
            <div id="support" class="bg-neutral-950/40 border border-neutral-900 rounded-2xl p-6 flex flex-col h-full shadow-2xl relative overflow-hidden" x-data="clientChat()">
                <div class="absolute -right-24 -top-24 w-52 h-52 bg-white/2 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Chat Header -->
                <div class="flex justify-between items-center mb-5 border-b border-neutral-900/60 pb-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-neutral-950 border border-neutral-900 flex items-center justify-center shadow-lg">
                            <span class="text-white font-extrabold text-xs">CS</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-tight uppercase tracking-wider">Dukungan Pelanggan</h3>
                            <p class="text-[10px] text-neutral-450 flex items-center gap-1.5 mt-1 font-semibold">
                                <span class="relative flex h-1.5 w-1.5" x-show="adminOnline">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                </span>
                                <span class="h-1.5 w-1.5 rounded-full bg-neutral-600" x-show="!adminOnline"></span>
                                <span x-text="adminOnline ? 'Online. Membalas instan.' : 'Offline. Kami akan segera kembali.'"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Message Listing Area -->
                <div class="flex-1 overflow-y-auto mb-4 space-y-4 px-3 py-4 rounded-xl bg-black/60 border border-neutral-900/60 shadow-inner scrollbar-thin" id="clientMessagesBox">
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex items-start gap-3 group w-full relative z-10" :class="!msg.is_admin ? 'justify-end' : ''">
                            
                            <!-- Support Avatar -->
                            <div x-show="msg.is_admin" class="w-7 h-7 rounded-lg bg-neutral-950 border border-neutral-900 flex items-center justify-center text-[9px] font-bold text-white shrink-0 mt-0.5">
                                CS
                            </div>

                            <div class="flex flex-col max-w-[75%] relative" :class="!msg.is_admin ? 'items-end' : 'items-start'">
                                <template x-if="msg.image_path">
                                    <div class="p-1 rounded-2xl shadow-md overflow-hidden bg-neutral-950 border border-neutral-900 mb-1">
                                        <img :src="'{{ asset('storage') }}/' + msg.image_path" class="max-w-full h-auto rounded-xl max-h-60 object-contain cursor-pointer active:scale-98 transition-transform" @click="window.open('{{ asset('storage') }}/' + msg.image_path, '_blank')">
                                    </div>
                                </template>
                                
                                <div class="px-5 py-3.5 rounded-2xl text-xs sm:text-sm leading-relaxed shadow-lg font-medium" x-show="msg.message"
                                        :class="!msg.is_admin ? 'bg-white text-black font-semibold rounded-tr-sm shadow-[0_4px_16px_rgba(255,255,255,0.06)]' : 'bg-neutral-950 text-neutral-200 rounded-tl-sm border border-neutral-900'">
                                    <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                </div>
                                
                                <div class="text-[9px] text-neutral-500 flex items-center gap-2 mt-1.5 px-1 font-semibold uppercase tracking-wider">
                                    <span x-text="new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                    <button @click="removeChatMessage(msg.id)" x-show="!msg.is_admin" class="text-neutral-550 hover:text-red-500 transition-colors p-1 -m-1 rounded-lg hover:bg-red-500/5 cursor-pointer" title="Hapus Pesan">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                    <svg x-show="!msg.is_admin && msg.is_read" class="w-3.5 h-3.5 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Empty Chat logs state -->
                    <div x-show="messages.length === 0" class="h-full flex flex-col items-center justify-center text-neutral-500 italic py-10 w-full">
                        <div class="w-14 h-14 bg-neutral-950 border border-neutral-900 rounded-2xl flex items-center justify-center mb-4 shadow-xl">
                            <svg class="w-6 h-6 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025 10.321 10.321 0 01-2.164-3.623C2.26 14.153 2 13.09 2 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                        </div>
                        <p class="text-neutral-500 text-xs font-bold uppercase tracking-widest">Belum ada pesan</p>
                        <p class="text-[10px] text-neutral-500 mt-1 max-w-xs text-center leading-relaxed font-semibold">Tulis pesan atau lampirkan invoice pembayaran jika ingin melakukan verifikasi.</p>
                    </div>
                </div>

                <!-- Attachment Preview Capsule -->
                <div x-show="imagePreview" class="px-4 py-2 bg-neutral-950 border border-neutral-900 flex items-center gap-3 rounded-xl mb-2 relative z-10">
                    <div class="relative w-10 h-10 bg-black rounded-lg overflow-hidden border border-neutral-900">
                        <img :src="imagePreview" class="w-full h-full object-cover opacity-60">
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-[10px] font-mono text-neutral-400 truncate" x-text="selectedFile ? selectedFile.name : 'File tidak dikenal'"></p>
                    </div>
                    <button @click="clearFile" class="p-1 text-red-500 hover:text-red-400 active:scale-95 transition-transform cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Input area form -->
                <form @submit.prevent="sendMessage" class="flex gap-2 bg-neutral-950 border border-neutral-900 p-2 rounded-xl items-center mt-auto relative z-10">
                    <button type="button" @click="$refs.fileInput.click()" class="p-2.5 text-neutral-500 hover:text-white rounded-lg hover:bg-neutral-900 transition-colors shrink-0 active:scale-95 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </button>
                    <input type="file" x-ref="fileInput" @change="onFileSelected" class="hidden" accept="image/*">
                    
                    <div class="relative flex-1 min-w-0">
                        <input type="text" x-model="newMessage" placeholder="Tulis pesan atau lampirkan bukti..." class="w-full bg-transparent border-none text-white focus:ring-0 outline-none text-xs sm:text-sm font-semibold py-2 px-1 placeholder-neutral-600" autocomplete="off">
                    </div>
                    
                    <button type="submit" class="btn-primary h-10 w-10 flex items-center justify-center p-0 rounded-xl active:scale-[0.98] shrink-0" :disabled="sending || (newMessage.trim().length === 0 && !selectedFile)">
                        <svg x-show="!sending" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"></path></svg>
                        <svg x-show="sending" class="animate-spin h-4 w-4 text-black shrink-0" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Live chat logic -->
    <script>
        const initClientChatComponent = () => {
            Alpine.data('clientChat', () => ({
                messages: [],
                deletingIds: new Set(),
                isProcessing: false,
                adminOnline: false,
                pollingInterval: null,
                sending: false,
                newMessage: '',
                selectedFile: null,
                imagePreview: null,

                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('message')) {
                        this.newMessage = urlParams.get('message');
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                    this.fetchMessages();
                    this.pollingInterval = setInterval(() => {
                        this.fetchMessages(true);
                    }, 3000);
                },

                destroy() {
                    if (this.pollingInterval) clearInterval(this.pollingInterval);
                },

                onFileSelected(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    if (!file.type.startsWith('image/')) {
                        alert('Silakan pilih file gambar.');
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
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                },

                async fetchMessages(isPolling = false) {
                    if (this.isProcessing && isPolling) return;
                    try {
                        const res = await fetch(`{{ route('client.chat.messages') }}`);
                        if (res.ok) {
                            const data = await res.json();
                            this.adminOnline = data.admin_online;
                            const msgs = data.messages || [];
                            const newMessages = msgs.filter(m => !this.deletingIds.has(m.id));
                            
                            if (JSON.stringify(this.messages) !== JSON.stringify(newMessages)) {
                                this.messages = newMessages;
                                if (!isPolling) this.$nextTick(() => this.scrollToBottom());
                            }
                        }
                    } catch(e) { console.error('Error fetching chat:', e); }
                },

                async sendMessage() {
                    if ((!this.newMessage.trim() && !this.selectedFile) || this.sending) return;
                    this.sending = true;
                    this.isProcessing = true;

                    const formData = new FormData();
                    formData.append('message', this.newMessage);
                    if (this.selectedFile) {
                        formData.append('image', this.selectedFile);
                    }

                    try {
                        const res = await fetch(`{{ route('client.chat.store') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        if (res.ok) {
                            this.newMessage = '';
                            this.clearFile();
                            await this.fetchMessages();
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    } catch(e) { console.error('Send error:', e); }
                    this.sending = false;
                    this.isProcessing = false;
                },

                async removeChatMessage(id) {
                    if (!confirm('Hapus pesan ini secara permanen?')) return;
                    
                    this.deletingIds.add(id);
                    this.messages = this.messages.filter(m => m.id !== id);
                    this.isProcessing = true;
                    
                    try {
                        const formData = new FormData();
                        formData.append('_method', 'DELETE');
                        formData.append('_token', document.head.querySelector('meta[name="csrf-token"]').content);

                        const res = await fetch(`{{ url('dashboard/chat') }}/${id}`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' },
                            body: formData
                        });
                        
                        if (!res.ok) {
                            const data = await res.json();
                            alert('Gagal menghapus: ' + (data.error || 'Terjadi kesalahan'));
                            this.deletingIds.delete(id);
                            await this.fetchMessages();
                        }
                    } catch(e) { 
                        console.error('Delete error:', e); 
                        alert('Error koneksi: ' + e.message);
                        this.deletingIds.delete(id);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                scrollToBottom() {
                    const box = document.getElementById('clientMessagesBox');
                    if (box) box.scrollTop = box.scrollHeight;
                }
            }));
        };

        if (window.Alpine) {
            initClientChatComponent();
        } else {
            document.addEventListener('alpine:init', initClientChatComponent);
        }
    </script>
</x-app-layout>
