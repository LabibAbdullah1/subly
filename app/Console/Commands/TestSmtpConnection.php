<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSmtpConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Email tujuan pengiriman test (default: MAIL_ADMIN_EMAIL)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menguji koneksi dan pengiriman email menggunakan konfigurasi SMTP di .env';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Menguji koneksi SMTP...');

        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');
        $encryption = env('MAIL_ENCRYPTION') ?? config('mail.mailers.smtp.encryption');
        $fromAddress = config('mail.from.address');
        
        $adminEmail = $this->argument('email') ?? config('mail.admin_email') ?? config('mail.from.address');

        $this->line("Mailer Default: <fg=cyan>{$mailer}</>");
        $this->line("Host SMTP:      <fg=cyan>{$host}</>");
        $this->line("Port SMTP:      <fg=cyan>{$port}</>");
        $this->line("Username:       <fg=cyan>{$username}</>");
        $this->line("Encryption:     <fg=cyan>{$encryption}</>");
        $this->line("From Address:   <fg=cyan>{$fromAddress}</>");
        $this->line("Target Email:   <fg=yellow>{$adminEmail}</>");

        if (empty($adminEmail)) {
            $this->error("Target email tidak ditentukan dan MAIL_ADMIN_EMAIL / MAIL_FROM_ADDRESS belum diatur di .env!");
            return Command::FAILURE;
        }

        $this->line("\nMencoba mengirim email uji coba...");

        try {
            Mail::raw('Halo, ini uji coba sistem SMTP pada proyek Subly! Jika Anda menerima email ini, sistem SMTP Anda telah berhasil terhubung dan berfungsi dengan baik.', function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('[Subly] 🧪 Uji Coba Koneksi SMTP');
            });

            $this->info("\n[SUKSES] Email uji coba berhasil dikirim ke {$adminEmail}!");
            $this->info("Silakan periksa kotak masuk (inbox) atau folder spam pada email tersebut.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("\n[GAGAL] Gagal mengirim email uji coba.");
            $this->error("Pesan Error: " . $e->getMessage());
            $this->line("\nTips Solusi:");
            $this->line("1. Pastikan port (465 untuk SSL, 587 untuk TLS) sesuai dengan enkripsi.");
            $this->line("2. Pastikan username dan password akun email Anda benar.");
            $this->line("3. Jika menggunakan cPanel SMTP, pastikan hosting Anda mengizinkan koneksi SMTP luar (atau gunakan localhost/127.0.0.1 jika web/SMTP di server yang sama).");
            return Command::FAILURE;
        }
    }
}
