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
        $this->info('Starting expired subdomains and deployment cleanup...');

        // Find active subdomains whose expired_at date has passed
        $expiredSubdomains = \App\Models\Subdomain::where('status', 'active')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->with('deployments')
            ->get();

        $subdomainsDeactivated = 0;
        $filesDeleted = 0;

        foreach ($expiredSubdomains as $subdomain) {
            // Update subdomain status from active to inactive
            $subdomain->update(['status' => 'inactive']);
            $subdomainsDeactivated++;

            // Clean up all associated deployment ZIP archives
            foreach ($subdomain->deployments as $deployment) {
                if ($deployment->zip_path && \Storage::exists($deployment->zip_path)) {
                    \Storage::delete($deployment->zip_path);
                    $filesDeleted++;
                }

                $deployment->update([
                    'zip_path' => null,
                    'status' => 'error',
                    'admin_note' => 'ZIP file removed due to subdomain expiry.'
                ]);
            }
        }

        $this->info("Cleanup finished. Subdomains deactivated: $subdomainsDeactivated. Files deleted: $filesDeleted.");
    }
}
