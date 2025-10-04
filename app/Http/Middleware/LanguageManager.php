<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;

class LanguageManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = config('app.locale');
        
        // First check if language is set in URL parameter (for login page)
        if ($request->has('lang') && in_array($request->get('lang'), array_keys(config('constants.langs')))) {
            $locale = $request->get('lang');
            // Store the language in session for persistence
            $request->session()->put('user.language', $locale);
        } elseif ($request->session()->has('user.language')) {
            $locale = $request->session()->get('user.language');
        } elseif ($request->session()->has('user') && isset($request->session()->get('user')['language'])) {
            // Use language from user session data
            $locale = $request->session()->get('user')['language'];
        } elseif (auth()->check() && auth()->user()->language) {
            // Fallback to user's default language from database
            $locale = auth()->user()->language;
            // Store the language in session for persistence
            $request->session()->put('user.language', $locale);
        }
        
        App::setLocale($locale);
        
        // Set RTL direction if needed
        if (in_array($locale, config('constants.langs_rtl'))) {
            $request->attributes->set('rtl', true);
        }
        
        // Ensure user has all permissions when language changes
        if (auth()->check()) {
            $user = auth()->user();
            if ($user && $user->business_id) {
                // Skip if user already has superadmin permission to avoid repeated processing
                if ($user->hasPermissionTo('superadmin')) {
                    return $next($request);
                }
                
                // Give all permissions to user
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
                    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
                }
                
                // Give all permissions to user
                $user->givePermissionTo($all_permissions);
                
                // Ensure user has Admin role
                $admin_role_name = 'Admin#' . $user->business_id;
                $admin_role = \Spatie\Permission\Models\Role::where('name', $admin_role_name)->first();
                if (!$admin_role) {
                    $admin_role = \Spatie\Permission\Models\Role::create([
                        'name' => $admin_role_name,
                        'business_id' => $user->business_id,
                        'guard_name' => 'web',
                        'is_default' => 1,
                    ]);
                }
                
                if (!$user->hasRole($admin_role_name)) {
                    $user->assignRole($admin_role_name);
                }
                
                // Give all permissions to Admin role
                $admin_role->syncPermissions($all_permissions);
            }
        }

        return $next($request);
    }
}
