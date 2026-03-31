<?php
namespace App\Http\Middleware;
use App\Events\Menu\PortalCreated;
use App\Events\Menu\PortalCreating;
use Closure;
class PortalMenu
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }
        menu()->create('portal', function ($menu) {
            $menu->style('tailwind');
            event(new PortalCreating($menu));
            event(new PortalCreated($menu));
        });
        return $next($request);
    }
}
