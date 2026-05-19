<x-emails.admin.layout>
    <span class="badge badge-green">💳 Pembayaran Baru</span>
    <h1>Ada Pembayaran Masuk!</h1>
    <p>Seorang client baru saja melakukan pembayaran di platform Subly. Berikut detail transaksinya:</p>

    <div class="info-card">
        <table>
            <tr>
                <td>👤 Client</td>
                <td>{{ $payment->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>📧 Email</td>
                <td>{{ $payment->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td>📦 Paket</td>
                <td>{{ $payment->plan->name ?? '-' }}</td>
            </tr>
            @if($payment->subdomain)
            <tr>
                <td>🌐 Subdomain</td>
                <td class="highlight">{{ $payment->subdomain->name }}.subly.my.id</td>
            </tr>
            @endif
            <tr>
                <td>💰 Jumlah</td>
                <td class="highlight">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            @if($payment->unique_code)
            <tr>
                <td>🔢 Kode Unik</td>
                <td>{{ $payment->unique_code }}</td>
            </tr>
            @endif
            <tr>
                <td>🆔 Transaction ID</td>
                <td>{{ $payment->transaction_id }}</td>
            </tr>
            <tr>
                <td>📅 Waktu</td>
                <td>{{ $payment->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>📊 Status</td>
                <td>
                    @if($payment->status === 'pending')
                        <span style="color:#fbbf24;">⏳ Menunggu Konfirmasi</span>
                    @elseif($payment->status === 'success')
                        <span style="color:#34d399;">✅ Sukses</span>
                    @else
                        <span style="color:#f87171;">❌ Gagal</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($payment->status === 'pending')
    <p>⚡ Segera verifikasi pembayaran ini di panel admin untuk mengaktifkan layanan client.</p>
    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-primary">
        Lihat & Konfirmasi Pembayaran →
    </a>
    @endif
</x-emails.admin.layout>
