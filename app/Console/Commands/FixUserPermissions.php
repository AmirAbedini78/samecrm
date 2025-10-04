<?php

namespace App\Console\Commands;

use App\Business;
use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:user-permissions {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user permissions and roles';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = $this->argument('user_id');
        
        if ($user_id) {
            $users = User::where('id', $user_id)->get();
        } else {
            $users = User::whereNotNull('business_id')->get();
        }
        
        foreach ($users as $user) {
            $this->info("Processing user: {$user->username} (ID: {$user->id})");
            
            if (!$user->business_id) {
                $this->warn("User {$user->username} has no business_id, skipping...");
                continue;
            }
            
            // Check if user has Admin role
            $admin_role_name = 'Admin#' . $user->business_id;
            $admin_role = Role::where('name', $admin_role_name)->first();
            
            if (!$admin_role) {
                $this->info("Creating Admin role for business {$user->business_id}");
                $admin_role = Role::create([
                    'name' => $admin_role_name,
                    'business_id' => $user->business_id,
                    'guard_name' => 'web',
                    'is_default' => 1,
                ]);
            }
            
            // Check if user has the Admin role
            if (!$user->hasRole($admin_role_name)) {
                $this->info("Assigning Admin role to user {$user->username}");
                $user->assignRole($admin_role_name);
            }
            
            // Create all necessary permissions
            $permissions = [
                'dashboard.data',
                'user.view',
                'user.create',
                'user.update',
                'user.delete',
                'contact.view',
                'contact.create',
                'contact.update',
                'contact.delete',
                'product.view',
                'product.create',
                'product.update',
                'product.delete',
                'purchase.view',
                'purchase.create',
                'purchase.update',
                'purchase.delete',
                'sell.view',
                'sell.create',
                'sell.update',
                'sell.delete',
                'report.view',
                'business_settings',
                'access_all_locations',
                'view_cash_register',
                'close_cash_register',
                'print_invoice',
                'view_export_buttons',
                'backup',
                'superadmin',
                'manage_modules'
            ];
            
            // Create permissions if they don't exist
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
            
            // Give all permissions to Admin role
            $admin_role->syncPermissions($permissions);
            
            $this->info("Fixed permissions for user {$user->username}");
        }
        
        $this->info('User permissions fixed successfully!');
        return 0;
    }
}
