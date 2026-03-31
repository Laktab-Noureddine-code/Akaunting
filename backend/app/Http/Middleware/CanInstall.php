<?php
namespace App\Http\Middleware;
use Closure;
class CanInstall
{
    public function handle($request, Closure $next)
    {
        if (env('APP_INSTALLED', false) == false) {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
