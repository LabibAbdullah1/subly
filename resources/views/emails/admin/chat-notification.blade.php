<x-emails.admin.layout>
    <span class="badge badge-blue">💬 Pesan Chat Baru</span>
    <h1>Client Mengirim Pesan!</h1>
    <p>Seorang client mengirim pesan baru melalui fitur live chat di Subly.</p>

    <div class="info-card">
        <table>
            <tr>
                <td>👤 Client</td>
                <td>{{ $chat->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>📧 Email</td>
                <td>{{ $chat->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td>📅 Waktu</td>
                <td>{{ $chat->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
            </tr>
            @if($chat->image_path)
            <tr>
                <td>🖼️ Lampiran</td>
                <td><span style="color:#93c5fd;">Ada gambar terlampir</span></td>
            </tr>
            @endif
        </table>
    </div>

    @if($chat->message)
    <p style="margin-bottom: 8px; color: #64748b; font-size: 13px;">Isi Pesan:</p>
    <div class="message-box">
        "{{ $chat->message }}"
    </div>
    @else
    <div class="message-box">
        <em style="color: #64748b;">[Client melampirkan gambar tanpa pesan teks]</em>
    </div>
    @endif

    <p>Segera balas pesan client ini agar mendapat pengalaman terbaik. 🎯</p>
    <a href="{{ url('/admin/chats') }}" class="btn btn-primary">
        Balas Pesan Sekarang →
    </a>
</x-emails.admin.layout>
