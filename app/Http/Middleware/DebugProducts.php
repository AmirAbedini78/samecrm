<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class DebugProducts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Debug products request
        if ($request->is('products*')) {
            Log::info('Products request: ' . $request->url());
            Log::info('User: ' . (auth()->user() ? auth()->user()->username : 'Not logged in'));
            Log::info('Business ID: ' . session('user.business_id'));
            Log::info('Products count: ' . \App\Product::count());
            Log::info('Products with business_id=1: ' . \App\Product::where('business_id', 1)->count());
        }
        
        return $next($request);
    }
}
