<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:permissions {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user permissions and roles';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $username = $this->argument('username');
        
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            $this->error("User with username '{$username}' not found.");
            return 1;
        }
        
        $this->info("Testing permissions for user: {$user->first_name} {$user->last_name} ({$user->username})");
        $this->info("Business ID: {$user->business_id}");
        $this->info("User Type: {$user->user_type}");
        $this->info("Allow Login: " . ($user->allow_login ? 'Yes' : 'No'));
        
        $this->line('');
        $this->info("=== ROLES ===");
        $roles = $user->getRoleNames();
        if ($roles->count() > 0) {
            foreach ($roles as $role) {
                $this->line("- {$role}");
            }
        } else {
            $this->line("No roles assigned");
        }
        
        $this->line('');
        $this->info("=== PERMISSIONS ===");
        $permissions = $user->getAllPermissions();
        if ($permissions->count() > 0) {
            foreach ($permissions as $permission) {
                $this->line("- {$permission->name}");
            }
        } else {
            $this->line("No permissions assigned");
        }
        
        $this->line('');
        $this->info("=== PERMISSION TESTS ===");
        $test_permissions = [
            'product.view',
            'product.create',
            'product.update',
            'product.delete',
            'user.view',
            'user.create',
            'business_settings',
            'superadmin'
        ];
        
        foreach ($test_permissions as $permission) {
            $has_permission = $user->can($permission);
            $status = $has_permission ? '✓' : '✗';
            $this->line("{$status} {$permission}");
        }
        
        return 0;
    }
}
