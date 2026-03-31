<?php
namespace App\Http\Middleware;
use Closure;
class RedirectIfNotInstalled
{
    public function handle($request, Closure $next)
    {
        if (config('app.installed', false) == true) {
            return $next($request);
        }
        if ($request->isInstall()) {
            return $next($request);
        }
        return redirect()->route('install.requirements');
    }
}
