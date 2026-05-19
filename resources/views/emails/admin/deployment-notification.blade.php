<x-emails.admin.layout>
    <span class="badge badge-purple">🚀 Deployment Baru</span>
    <h1>File Deployment Baru Masuk!</h1>
    <p>Seorang client baru saja mengupload file deployment baru dan sedang dalam antrian untuk diproses.</p>

    <div class="info-card">
        <table>
            <tr>
                <td>👤 Client</td>
                <td>{{ $deployment->subdomain->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>📧 Email</td>
                <td>{{ $deployment->subdomain->user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td>🌐 Subdomain</td>
                <td class="highlight">{{ $deployment->subdomain->name ?? '-' }}.subly.my.id</td>
            </tr>
            <tr>
                <td>🔢 Versi</td>
                <td>v{{ $deployment->version ?? '?' }}</td>
            </tr>
            <tr>
                <td>📦 Ukuran ZIP</td>
                <td>{{ $deployment->zip_size ? round($deployment->zip_size / 1048576, 2) . ' MB' : '-' }}</td>
            </tr>
            <tr>
                <td>💾 Ukuran Extracted</td>
                <td>{{ $deployment->extracted_size ? round($deployment->extracted_size / 1048576, 2) . ' MB' : '-' }}</td>
            </tr>
            <tr>
                <td>📊 Status</td>
                <td><span style="color: #93c5fd;">⏳ Queued (Dalam Antrian)</span></td>
            </tr>
            <tr>
                <td>📅 Waktu Upload</td>
                <td>{{ $deployment->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
            </tr>
            @if($deployment->notes)
            <tr>
                <td>📝 Catatan</td>
                <td>{{ $deployment->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    <p>File deployment sudah masuk ke antrian dan akan segera diproses oleh sistem secara otomatis.</p>

    <a href="{{ url('/admin/deployments') }}" class="btn btn-primary">
        Lihat Deployment Queue →
    </a>
</x-emails.admin.layout>
