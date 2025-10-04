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
            
            // Check if user is superadmin first
            if (in_array(strtolower($user->username), explode(',', strtolower($administrator_list)))) {
                return true;
            }
            
            // Then check if user has Admin role
            if ($user->hasRole('Admin#'.$user->business_id)) {
                return true;
            }
        });
    }
}
