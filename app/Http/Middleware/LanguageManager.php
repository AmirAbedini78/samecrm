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
        
        // Only handle language persistence, not permission granting
        if (auth()->check()) {
            $user = auth()->user();
            if ($user && $user->business_id) {
                // Skip if user already has superadmin permission to avoid repeated processing
                if ($user->hasPermissionTo('superadmin')) {
                    return $next($request);
                }
                
                // Only ensure language persistence, don't grant permissions
                // Store user's language preference in session for persistence
                if (!$request->session()->has('language')) {
                    $request->session()->put('language', $user->language ?? 'en');
                }
            }
        }

        return $next($request);
    }
}
