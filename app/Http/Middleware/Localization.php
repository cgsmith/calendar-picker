<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    public function handle(Request $request, Closure $next): Response
    {
        if (($language = $request->get('lang')) && in_array($language, config('app.available_locales'))) {
            // a valid language is sent in the querystring - set it in the session for future use
            $request->session()->put('language', $language);
        } else {
            // otherwise use the session language or the fallback
            $language = $request->session()->get('language', config('app.fallback_locale'));
        }

        App::setLocale($language);

        return $next($request);
    }
}
