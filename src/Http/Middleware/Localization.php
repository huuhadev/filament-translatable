<?php

namespace Huuhadev\FilamentTranslatable\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Huuhadev\FilamentTranslatable\FilamentTranslatablePlugin;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     */
    public function handle($request, Closure $next): mixed
    {
        $locale = FilamentTranslatablePlugin::get()->getActiveLocale();

        app()->setLocale($locale);

        locale_set_default($locale);

        return $next($request);
    }
}
