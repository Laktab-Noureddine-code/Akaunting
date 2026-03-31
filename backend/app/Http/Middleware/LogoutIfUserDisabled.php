<?php
namespace App\Http\Middleware;
use Closure;
class LogoutIfUserDisabled
{
    public function handle($request, Closure $next)
    {
        $user = user();
        if (!$user || $user->enabled) {
            return $next($request);
        }
        if (request_is_api($request)) {
            if ($user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }
            return response()->json([
                'success' => false,
                'message' => 'Your account has been disabled.',
            ], 403);
        }
        auth()->logout();
        return redirect()->route('login');
    }
}
