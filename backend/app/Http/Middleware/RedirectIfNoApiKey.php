<?php
namespace App\Http\Middleware;
use Closure;
class RedirectIfNoApiKey
{
    public function handle($request, Closure $next)
    {
        if ($request->get('alias') == 'core') {
            return $next($request);
        }
        if (setting('apps.api_key')) {
            return $next($request);
        }
        return redirect()->route('apps.api-key.create');
    }
}
