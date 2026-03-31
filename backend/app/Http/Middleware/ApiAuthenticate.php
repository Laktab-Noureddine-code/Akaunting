<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class ApiAuthenticate
{
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Invalid or expired token.',
                ], 401);
            }
            Auth::setUser($user);
            return $next($request);
        }
        return Auth::onceBasic() ?: $next($request);
    }
}
