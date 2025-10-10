<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            $administrator_list = config('constants.administrator_usernames');
            
            // Only give full access to superadmin users (not all Admin role users)
            if (in_array(strtolower($user->username), explode(',', strtolower($administrator_list)))) {
                return true;
            }

            // Grant full access to users with Admin role for their business
            $admin_role = 'Admin#' . ($user->business_id ?? session('business.id'));
            if ($admin_role && $user->hasRole($admin_role)) {
                return true;
            }
        });
    }
}
