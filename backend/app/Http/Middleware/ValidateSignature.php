<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
class ValidateSignature
{
    public function handle($request, Closure $next)
    {
        if ($this->hasValidSignature($request)) {
            return $next($request);
        }
        throw new InvalidSignatureException;
    }
    public function hasValidSignature(Request $request, $absolute = true)
    {
        return $this->hasCorrectSignature($request, $absolute)
        && $this->signatureHasNotExpired($request);
    }
    public function hasCorrectSignature(Request $request, $absolute = true)
    {
        $url = $absolute ? $request->url() : '/' . $request->path();
        $original = rtrim($url . '?' . Arr::query(
            Arr::only($request->query(), ['company_id'])
        ) . Arr::query(
            Arr::only($request->query(), ['expires'])
        ), '?');
        $signature = hash_hmac('sha256', $original, call_user_func(function () {
            return config('app.key');
        }));
        return hash_equals($signature, (string) $request->query('signature', ''));
    }
    public function signatureHasNotExpired(Request $request)
    {
        $expires = $request->query('expires');
        return !($expires && Carbon::now()->getTimestamp() > $expires);
    }
}
