<?php
namespace App\Http\Controllers\Auth;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Login as Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Str;
class Login extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/';
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }
    public function create()
    {
        return view('auth.login.create');
    }
    public function store(Request $request)
    {
        if (! auth()->attempt($request->only('email', 'password'), $request->get('remember', false))) {
            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.failed'),
                'data' => null,
                'redirect' => null,
            ]);
        }
        $user = user();
        if (! $user->enabled) {
            $this->logout();
            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.disabled'),
                'data' => null,
                'redirect' => null,
            ]);
        }
        $company = $user->withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });
        if (! $company) {
            $this->logout();
            return response()->json([
                'status' => null,
                'success' => false,
                'error' => true,
                'message' => trans('auth.error.no_company'),
                'data' => null,
                'redirect' => null,
            ]);
        }
        if ($user->isCustomer()) {
            $path = session('url.intended', '');
            if (!Str::startsWith($path, $company->id . '/portal')) {
                $path = route('portal.dashboard', ['company_id' => $company->id]);
            }
            return response()->json([
                'status' => null,
                'success' => true,
                'error' => false,
                'message' => trans('auth.login_redirect'),
                'data' => null,
                'redirect' => url($path),
            ]);
        }
        $url = route($user->landing_page, ['company_id' => $company->id]);
        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => trans('auth.login_redirect'),
            'data' => null,
            'redirect' => redirect()->intended($url)->getTargetUrl(),
        ]);
    }
    public function destroy()
    {
        $this->logout();
        return redirect()->route('login');
    }
    public function logout()
    {
        auth()->logout();
        if (config('session.driver') == 'database') {
            $request = app('Illuminate\Http\Request');
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->getHandler()->destroy($request->session()->getId());
        }
    }
}
