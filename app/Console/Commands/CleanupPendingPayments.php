<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Carbon\Carbon;

class CleanupPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup pending payments older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting pending payments cleanup...');

        $expiredCount = Payment::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subDay())
            ->update(['status' => 'failed']);

        $this->info("Cleanup finished. $expiredCount pending payments marked as failed.");
    }
}
