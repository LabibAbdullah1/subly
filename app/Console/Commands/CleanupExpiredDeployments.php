<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupExpiredDeployments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployments:cleanup';
    protected $description = 'Remove ZIP files for expired deployments';

    public function handle()
    {
        $this->info('Starting deployment cleanup...');

        // Find subdomains whose highest/latest payment is expired
        // Expiry = payment.created_at + plan.duration_months
        $deploymentsProcessed = 0;
        $filesDeleted = 0;

        $deployments = \App\Models\Deployment::whereNotNull('zip_path')
            ->with(['subdomain.user.payments' => function($q) {
                $q->where('status', 'success')->latest();
            }, 'subdomain.user.payments.plan'])
            ->get();

        foreach ($deployments as $deployment) {
            $latestPayment = $deployment->subdomain->user->payments->first();
            
            if (!$latestPayment) {
                $this->warn("No success payment found for user: " . ($deployment->subdomain->user->email ?? 'Unknown'));
                continue;
            }

            $expiryDate = $latestPayment->created_at->addMonths($latestPayment->plan->duration_months);

            if (now()->greaterThan($expiryDate)) {
                if (\Storage::exists($deployment->zip_path)) {
                    \Storage::delete($deployment->zip_path);
                    $filesDeleted++;
                }

                // We keep the DB record but mark it as cleaned/expired if we had such column
                // Or just clear the path to indicate file is gone
                $deployment->update([
                    'zip_path' => null,
                    'status' => 'error',
                    'admin_note' => 'ZIP file removed due to plan expiry.'
                ]);

                $deploymentsProcessed++;
            }
        }

        $this->info("Cleanup finished. Deployments processed: $deploymentsProcessed. Files deleted: $filesDeleted.");
    }
}
