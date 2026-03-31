<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (!auth()->guard($guard)->check()) {
                continue;
            }
            return redirect(user()->getLandingPageOfUser());
        }
        return $next($request);
    }
}
