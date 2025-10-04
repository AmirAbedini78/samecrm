<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix all user permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fixing permissions...');
        
        // All possible permissions
        $all_permissions = [
            'dashboard.data',
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'contact.view',
            'contact.create',
            'contact.update',
            'contact.delete',
            'supplier.view',
            'supplier.create',
            'supplier.update',
            'supplier.delete',
            'supplier.view_own',
            'customer.view',
            'customer.create',
            'customer.update',
            'customer.delete',
            'customer.view_own',
            'product.view',
            'product.create',
            'product.update',
            'product.delete',
            'product.view_own',
            'product.opening_stock',
            'purchase.view',
            'purchase.create',
            'purchase.update',
            'purchase.delete',
            'purchase.view_own',
            'view_own_purchase',
            'sell.view',
            'sell.create',
            'sell.update',
            'sell.delete',
            'sell.view_own',
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
            'report.view',
            'report.purchase',
            'report.sell',
            'report.stock',
            'report.tax',
            'report.profit_loss',
            'report.customer',
            'report.supplier',
            'report.product',
            'report.stock_adjustment',
            'report.stock_expiry',
            'report.product_quantity_alert',
            'report.daily_sale',
            'report.monthly_sale',
            'report.daily_purchase',
            'report.monthly_purchase',
            'report.cash_flow',
            'report.accounts',
            'report.trial_balance',
            'report.balance_sheet',
            'report.ledger',
            'report.day_book',
            'report.cash_register',
            'report.sales_commission_agent',
            'report.stock_report',
            'report.stock_summary',
            'report.product_stock_alert',
            'report.product_expiry_alert',
            'report.product_quantity_alert',
            'report.customer_group',
            'report.supplier_group',
            'report.product_group',
            'report.brand',
            'report.category',
            'report.unit',
            'report.tax_rate',
            'report.payment_method',
            'report.sales_representative',
            'report.sales_commission_agent',
            'report.customer_ledger',
            'report.supplier_ledger',
            'report.product_ledger',
            'report.stock_ledger',
            'report.purchase_ledger',
            'report.sell_ledger',
            'report.expense_ledger',
            'report.income_ledger',
            'report.cash_ledger',
            'report.bank_ledger',
            'report.credit_ledger',
            'report.debit_ledger',
            'business_settings',
            'system_settings',
            'location_settings',
            'printer_settings',
            'barcode_settings',
            'invoice_settings',
            'notification_settings',
            'backup_settings',
            'module_settings',
            'access_all_locations',
            'view_cash_register',
            'close_cash_register',
            'print_invoice',
            'view_export_buttons',
            'backup',
            'superadmin',
            'manage_modules',
            'manage_superadmin',
            'view_superadmin_dashboard'
        ];
        
        // Create permissions if they don't exist
        foreach ($all_permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->info("Created permission: {$permission}");
        }
        
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            $this->info("Processing user: {$user->username}");
            
            // Give all permissions to user
            $user->givePermissionTo($all_permissions);
            
            // Ensure user has Admin role
            if ($user->business_id) {
                $admin_role_name = 'Admin#' . $user->business_id;
                $admin_role = Role::where('name', $admin_role_name)->first();
                if (!$admin_role) {
                    $admin_role = Role::create([
                        'name' => $admin_role_name,
                        'business_id' => $user->business_id,
                        'guard_name' => 'web',
                        'is_default' => 1,
                    ]);
                    $this->info("Created role: {$admin_role_name}");
                }
                
                if (!$user->hasRole($admin_role_name)) {
                    $user->assignRole($admin_role_name);
                    $this->info("Assigned role to user: {$user->username}");
                }
                
                // Give all permissions to Admin role
                $admin_role->syncPermissions($all_permissions);
            }
        }
        
        $this->info('Permissions fixed successfully!');
        return 0;
    }
}
