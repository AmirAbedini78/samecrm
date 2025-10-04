<?php

namespace App\Http\Middleware;

use App\Business;
use App\Currency;
use App\Utils\BusinessUtil;
use Closure;
use Illuminate\Support\Facades\Auth;

class SetSessionData
{
    /**
     * Checks if session data is set or not for a user. If data is not set then set it.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->session()->has('user')) {
            $business_util = new BusinessUtil;

            $user = Auth::user();
            
            // Check if user has business_id
            if (!$user->business_id) {
                // Redirect to business registration if no business_id
                return redirect()->route('business.getRegister');
            }
            
            // Use language from session if available, otherwise use user's default language
            $user_language = $request->session()->get('user.language', $user->language);
            
            // If language is set in URL parameter, use it and store in session
            if ($request->has('lang') && in_array($request->get('lang'), array_keys(config('constants.langs')))) {
                $user_language = $request->get('lang');
            }
            
            // Update session with the language
            $request->session()->put('user.language', $user_language);
            
            $session_data = ['id' => $user->id,
                'surname' => $user->surname,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'business_id' => $user->business_id,
                'language' => $user_language,
            ];
            
            $business = Business::find($user->business_id);
            if (!$business) {
                // Log the error and try to get the first business or create a default one
                \Illuminate\Support\Facades\Log::error('Business not found for user: ' . $user->id . ' with business_id: ' . $user->business_id);
                
                // Try to get the first business
                $business = Business::first();
                if (!$business) {
                    // If no business exists, redirect to business registration
                    return redirect()->route('business.getRegister');
                }
                
                // Update user's business_id
                $user->business_id = $business->id;
                $user->save();
            }

            $currency = $business->currency;
            
            // If currency not found, try to get the first currency or create a default one
            if (!$currency) {
                $currency = Currency::first();
                if (!$currency) {
                    // Create a default currency if none exists
                    $currency = Currency::create([
                        'country' => 'United States',
                        'currency' => 'US Dollar',
                        'code' => 'USD',
                        'symbol' => '$',
                        'thousand_separator' => ',',
                        'decimal_separator' => '.',
                        'exchange_rate' => 1.0,
                        'is_default' => 1
                    ]);
                }
                
                // Update business with the currency
                $business->currency_id = $currency->id;
                $business->update(['currency_id' => $currency->id]);
            }
            
            $currency_data = ['id' => $currency->id,
                'code' => $currency->code,
                'symbol' => $currency->symbol,
                'thousand_separator' => $currency->thousand_separator,
                'decimal_separator' => $currency->decimal_separator,
            ];

            $request->session()->put('user', $session_data);
            $request->session()->put('business', $business);
            $request->session()->put('currency', $currency_data);

            //set current financial year to session
            $financial_year = $business_util->getCurrentFinancialYear($business->id);
            $request->session()->put('financial_year', $financial_year);
            
            // Ensure user has proper permissions
            $this->ensureUserPermissions($user, $business->id);
        } else {
            // Update language in existing session if URL parameter is set
            if ($request->has('lang') && in_array($request->get('lang'), array_keys(config('constants.langs')))) {
                $user_language = $request->get('lang');
                $request->session()->put('user.language', $user_language);
                
                // Update user session data
                $user_data = $request->session()->get('user');
                $user_data['language'] = $user_language;
                $request->session()->put('user', $user_data);
            }
            
            // Always ensure user has proper permissions when session exists
            $user = Auth::user();
            if ($user && $user->business_id) {
                $this->ensureUserPermissions($user, $user->business_id);
            }
            
            // Ensure currency session data exists
            if (!$request->session()->has('currency')) {
                $business = $request->session()->get('business');
                if ($business) {
                    $currency = $business->currency;
                    if (!$currency) {
                        $currency = Currency::first();
                        if (!$currency) {
                            $currency = Currency::create([
                                'country' => 'United States',
                                'currency' => 'US Dollar',
                                'code' => 'USD',
                                'symbol' => '$',
                                'thousand_separator' => ',',
                                'decimal_separator' => '.',
                                'exchange_rate' => 1.0,
                                'is_default' => 1
                            ]);
                        }
                    }
                    
                    $currency_data = ['id' => $currency->id,
                        'code' => $currency->code,
                        'symbol' => $currency->symbol,
                        'thousand_separator' => $currency->thousand_separator,
                        'decimal_separator' => $currency->decimal_separator,
                    ];
                    
                    $request->session()->put('currency', $currency_data);
                }
            }
        }

        return $next($request);
    }
    
    /**
     * Ensure user has proper permissions
     */
    private function ensureUserPermissions($user, $business_id)
    {
        try {
            $admin_role_name = 'Admin#' . $business_id;
            
            // Check if user is superadmin
            $administrator_list = config('constants.administrator_usernames');
            $is_superadmin = false;
            
            if ($administrator_list && in_array(strtolower($user->username), explode(',', strtolower($administrator_list)))) {
                $is_superadmin = true;
            }
            
            // Skip if user already has superadmin permission to avoid repeated processing
            if ($user->hasPermissionTo('superadmin')) {
                return;
            }
            
            // Check if Admin role exists
            $admin_role = \Spatie\Permission\Models\Role::where('name', $admin_role_name)->first();
            if (!$admin_role) {
                $admin_role = \Spatie\Permission\Models\Role::create([
                    'name' => $admin_role_name,
                    'business_id' => $business_id,
                    'guard_name' => 'web',
                    'is_default' => 1,
                ]);
            }
            
            // Check if user has the Admin role
            if (!$user->hasRole($admin_role_name)) {
                $user->assignRole($admin_role_name);
            }
            
            // Create essential permissions if they don't exist
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
            
            // Add superadmin specific permissions
            if ($is_superadmin) {
                $permissions = array_merge($permissions, [
                    'superadmin',
                    'backup',
                    'manage_modules',
                    'manage_superadmin',
                    'view_superadmin_dashboard'
                ]);
            }
            
            // Create permissions if they don't exist
            foreach ($permissions as $permission) {
                \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
            }
            
            // Give all permissions to Admin role
            $admin_role->syncPermissions($permissions);
            
            // If user is superadmin, give them superadmin permissions directly
            if ($is_superadmin) {
                $user->givePermissionTo(['superadmin', 'backup', 'manage_modules']);
            }
            
        } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error ensuring user permissions: ' . $e->getMessage());
        }
    }
}
