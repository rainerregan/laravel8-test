<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = null;

        // Jika user memiliki data locale, kita baca dan simpan di session
        if(Auth::check() && !Session::has('locale')) {
            $locale = $request->user()->locale;
            Session::put('locale', $locale);
        }

        // Jika terdapat parameter locale di request, maka kita akan set locale di session
        // Contoh: http://example.com?locale=en
        if($request->has('locale')){
            $locale = $request->get('locale');
            Session::put('locale', $locale);
        }

        // Mendapatkan data locale dari session
        $locale = Session::get('locale');

        // Jika locale tidak tersedia, maka gunakan locale dari config
        if($locale === null){
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
