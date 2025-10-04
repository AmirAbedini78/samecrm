<?php

namespace App\Http\Middleware;

use App\Currency;
use Closure;
use Illuminate\Http\Request;

class EnsureSessionData
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
            } else {
                // Create default currency session data
                $currency_data = ['id' => 1,
                    'code' => 'USD',
                    'symbol' => '$',
                    'thousand_separator' => ',',
                    'decimal_separator' => '.',
                ];
                
                $request->session()->put('currency', $currency_data);
            }
        }

        return $next($request);
    }
}
