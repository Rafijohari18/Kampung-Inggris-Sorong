<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;

class Languages
{
    private $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locales = [];
        foreach ($this->service->getAllLang() as $key) {
            $locales[$key->iso_codes] = $key->country;
        }

        // Check if the first segment matches a language code
        if (config('custom.language.multiple') == true) {
            if (!array_key_exists($request->segment(1), $locales)) {
                $segments = $request->segments();
                $segments = Arr::prepend($segments, config('custom.language.default'));
                return redirect()->to(implode('/', $segments));
            }
            app()->setLocale($request->segment(1));
            URL::defaults(['locale' => app()->getLocale()]);
        }

        return $next($request);
    }
}
