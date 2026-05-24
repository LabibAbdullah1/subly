<x-emails.admin.layout>
    <span class="badge badge-orange">⭐ Support Ticket / Feedback Baru</span>
    <h1>Ada Feedback dari Client!</h1>
    <p>Seorang client baru saja mengirimkan feedback atau support ticket di platform Subly.</p>

    <div class="info-card">
        <table>
            <tr>
                <td>👤 Client</td>
                <td>{{ $feedback->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>📧 Email</td>
                <td>{{ $feedback->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td>📦 Paket</td>
                <td>{{ $feedback->plan->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>⭐ Rating</td>
                <td>
                    @php $stars = $feedback->rating ?? 0; @endphp
                    <span class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $stars ? '★' : '☆' }}
                        @endfor
                    </span>
                    <span style="color: #94a3b8; font-size: 12px;">({{ $stars }}/5)</span>
                </td>
            </tr>
            <tr>
                <td>📅 Waktu</td>
                <td>{{ $feedback->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    @if($feedback->comment)
    <p style="margin-bottom: 8px; color: #64748b; font-size: 13px;">Komentar Client:</p>
    <div class="message-box">
        "{{ $feedback->comment }}"
    </div>
    @else
    <div class="message-box">
        <em style="color: #64748b;">[Client tidak memberikan komentar tambahan]</em>
    </div>
    @endif

    @if(($feedback->rating ?? 5) <= 3)
    <p style="color: #fca5a5;">⚠️ Rating rendah! Pertimbangkan untuk menghubungi client ini secara langsung.</p>
    @endif

    <a href="{{ url('/admin/feedbacks') }}" class="btn btn-primary">
        Lihat Semua Feedback →
    </a>
</x-emails.admin.layout>
