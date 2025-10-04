<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class MakeSuperadmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:superadmin {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user superadmin';

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
            $this->error("User with username '{$username}' not found!");
            return 1;
        }
        
        // Give superadmin permissions
        $superadmin_permissions = [
            'superadmin',
            'backup',
            'manage_modules',
            'manage_superadmin',
            'view_superadmin_dashboard'
        ];
        
        // Create permissions if they don't exist
        foreach ($superadmin_permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Give permissions to user
        $user->givePermissionTo($superadmin_permissions);
        
        $this->info("User '{$username}' is now a superadmin!");
        
        return 0;
    }
}
