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
            ->with('subdomain')
            ->get();

        foreach ($deployments as $deployment) {
            $subdomain = $deployment->subdomain;
            
            if (!$subdomain || !$subdomain->expired_at) {
                continue;
            }

            if (now()->greaterThan($subdomain->expired_at)) {
                if (\Storage::exists($deployment->zip_path)) {
                    \Storage::delete($deployment->zip_path);
                    $filesDeleted++;
                }

                $deployment->update([
                    'zip_path' => null,
                    'status' => 'error',
                    'admin_note' => 'ZIP file removed due to subdomain expiry.'
                ]);

                $deploymentsProcessed++;
            }
        }

        $this->info("Cleanup finished. Deployments processed: $deploymentsProcessed. Files deleted: $filesDeleted.");
    }
}
