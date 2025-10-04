<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Superadmin\Entities\Subscription;

class FixSubscriptionData extends Command
{
    protected $signature = 'fix:subscription-data';
    protected $description = 'Fix subscription package_details data';

    public function handle()
    {
        $subscriptions = Subscription::all();
        
        foreach ($subscriptions as $subscription) {
            if (is_string($subscription->package_details)) {
                $decoded = json_decode($subscription->package_details, true);
                if ($decoded) {
                    $subscription->package_details = $decoded;
                    $subscription->save();
                    $this->info("Fixed subscription ID: {$subscription->id}");
                }
            }
        }
        
        $this->info('✅ Subscription data fixed successfully!');
        return 0;
    }
}
