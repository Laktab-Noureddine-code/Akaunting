<?php
namespace App\Http\Middleware;
use App\Events\Menu\AdminCreated;
use App\Events\Menu\AdminCreating;
use Closure;
class AdminMenu
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }
        menu()->create('admin', function ($menu) {
            $menu->style('tailwind');
            event(new AdminCreating($menu));
            event(new AdminCreated($menu));
        });
        return $next($request);
    }
}
