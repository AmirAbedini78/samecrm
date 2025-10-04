<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Business;
use App\System;
use Modules\Superadmin\Entities\Package;
use Modules\Superadmin\Entities\Subscription;
use Carbon\Carbon;

class FixSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix subscription issues by creating default package and subscription';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fixing subscription issues...');

        // Set superadmin version
        System::setProperty('superadmin_version', '1.0.0');
        $this->info('✓ Set superadmin version');

        // Get first business
        $business = Business::first();
        if (!$business) {
            $this->error('No business found!');
            return 1;
        }

        $this->info("Business ID: {$business->id}");

        // Create default package if not exists
        $package = Package::first();
        if (!$package) {
            $package = Package::create([
                'name' => 'Ultimate Package',
                'price' => 0,
                'interval' => 'month',
                'interval_count' => 1,
                'trial_days' => 0,
                'sort_order' => 1,
                'is_active' => 1,
                'custom_permissions' => json_encode([]),
                'package_details' => json_encode([
                    'location_count' => 999,
                    'user_count' => 999,
                    'product_count' => 999,
                    'invoice_count' => 999,
                ])
            ]);
            $this->info('✓ Created default package');
        } else {
            $this->info('✓ Package already exists');
        }

        // Create active subscription if not exists
        $subscription = Subscription::active_subscription($business->id);
        if (!$subscription) {
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addYears(10); // 10 years subscription

            Subscription::create([
                'business_id' => $business->id,
                'package_id' => $package->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'approved',
                'package_details' => json_encode([
                    'location_count' => 999,
                    'user_count' => 999,
                    'product_count' => 999,
                    'invoice_count' => 999,
                ]),
                'paid_via' => 'offline',
                'payment_transaction_id' => 'manual_setup',
                'created_by' => 1,
            ]);
            $this->info('✓ Created active subscription');
        } else {
            $this->info('✓ Active subscription already exists');
        }

        $this->info('✅ Subscription issues fixed successfully!');
        return 0;
    }
}
