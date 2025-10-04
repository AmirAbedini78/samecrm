<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class GiveAllPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'give:all-permissions {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give all permissions to a user';

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
        
        // All possible permissions
        $all_permissions = [
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
            'manage_modules',
            'manage_superadmin',
            'view_superadmin_dashboard',
            'pos.view',
            'pos.create',
            'pos.update',
            'pos.delete',
            'stock.view',
            'stock.create',
            'stock.update',
            'stock.delete',
            'expense.view',
            'expense.create',
            'expense.update',
            'expense.delete',
            'account.view',
            'account.create',
            'account.update',
            'account.delete',
            'tax_rate.view',
            'tax_rate.create',
            'tax_rate.update',
            'tax_rate.delete',
            'unit.view',
            'unit.create',
            'unit.update',
            'unit.delete',
            'category.view',
            'category.create',
            'category.update',
            'category.delete',
            'brand.view',
            'brand.create',
            'brand.update',
            'brand.delete',
            'variation.view',
            'variation.create',
            'variation.update',
            'variation.delete',
            'location.view',
            'location.create',
            'location.update',
            'location.delete',
            'printer.view',
            'printer.create',
            'printer.update',
            'printer.delete',
            'barcode.view',
            'barcode.create',
            'barcode.update',
            'barcode.delete',
            'invoice_scheme.view',
            'invoice_scheme.create',
            'invoice_scheme.update',
            'invoice_scheme.delete',
            'invoice_layout.view',
            'invoice_layout.create',
            'invoice_layout.update',
            'invoice_layout.delete',
            'notification_template.view',
            'notification_template.create',
            'notification_template.update',
            'notification_template.delete',
            'system_settings',
            'business_settings',
            'location_settings',
            'printer_settings',
            'barcode_settings',
            'invoice_settings',
            'notification_settings',
            'backup_settings',
            'module_settings'
        ];
        
        // Create permissions if they don't exist
        foreach ($all_permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Give all permissions to user
        $user->givePermissionTo($all_permissions);
        
        $this->info("All permissions given to user '{$username}'!");
        
        return 0;
    }
}
