<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class DebugCategories
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
        // Debug categories request
        if ($request->is('categories*')) {
            Log::info('Categories request: ' . $request->url());
            Log::info('User: ' . (auth()->user() ? auth()->user()->username : 'Not logged in'));
            Log::info('Business ID: ' . session('user.business_id'));
            Log::info('Categories count: ' . \App\Category::count());
            Log::info('Categories with business_id=1: ' . \App\Category::where('business_id', 1)->count());
        }
        
        return $next($request);
    }
}
