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
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: Query param > Session > User preference > Browser > Default
        $locale = $request->query('lang')
            ?? Session::get('locale')
            ?? $request->user()?->preferred_locale
            ?? $this->getPreferredLocale($request)
            ?? config('app.locale');

        // Validate locale
        if (!in_array($locale, config('app.supported_locales', ['en', 'ar']))) {
            $locale = config('app.locale');
        }

        // Set locale
        App::setLocale($locale);

        // Store in session
        Session::put('locale', $locale);

        return $next($request);
    }

    /**
     * Get preferred locale from Accept-Language header
     */
    private function getPreferredLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language', '');
        $supported = config('app.supported_locales', ['en', 'ar']);

        foreach (explode(',', $acceptLanguage) as $lang) {
            $lang = strtolower(trim(explode(';', $lang)[0]));
            $lang = explode('-', $lang)[0]; // Get primary language tag

            if (in_array($lang, $supported)) {
                return $lang;
            }
        }

        return null;
    }
}
