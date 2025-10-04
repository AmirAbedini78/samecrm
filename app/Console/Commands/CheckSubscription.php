<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Superadmin\Entities\Subscription;

class CheckSubscription extends Command
{
    protected $signature = 'check:subscription';
    protected $description = 'Check subscription status';

    public function handle()
    {
        $subscription = Subscription::active_subscription(1);
        
        if ($subscription) {
            $this->info('Subscription found: ' . $subscription->id);
            $this->info('Package details: ' . json_encode($subscription->package_details));
            $this->info('Package name: ' . ($subscription->package->name ?? 'N/A'));
        } else {
            $this->error('No subscription found');
        }
        
        return 0;
    }
}
