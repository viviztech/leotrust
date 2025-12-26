<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales.
     */
    protected array $locales = ['en', 'ta'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is being changed via query parameter
        if ($request->has('lang') && in_array($request->query('lang'), $this->locales)) {
            $locale = $request->query('lang');
            Session::put('locale', $locale);
        } else {
            // Get locale from session, or default to English
            $locale = Session::get('locale', 'en');
        }

        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}

