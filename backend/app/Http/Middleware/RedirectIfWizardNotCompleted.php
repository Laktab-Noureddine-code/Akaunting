<?php
namespace App\Http\Middleware;
use Closure;
class RedirectIfWizardNotCompleted
{
    public function handle($request, Closure $next)
    {
        if (setting('wizard.completed', 0) == 1) {
            return $next($request);
        }
        if ($request->isWizard(company_id()) || $request->is(company_id() . '/settings/*')) {
            return $next($request);
        }
        return redirect()->route('wizard.edit');
    }
}
