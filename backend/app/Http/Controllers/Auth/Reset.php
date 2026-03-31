<?php
namespace App\Http\Controllers\Auth;
use App\Abstracts\Http\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request as BaseRequest;
use App\Http\Requests\Auth\Reset as Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
class Reset extends Controller
{
    use ResetsPasswords;
    public $redirectTo = '/';
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function create(BaseRequest $request, $token = null)
    {
        return view('auth.reset.create')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
    public function store(Request $request)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();
        $this->guard()->login($user);
    }
    protected function sendResetResponse($response)
    {
        $user = user();
        $company = $user::withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });
        if (! $company) {
            $this->guard()->logout();
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
            $this->redirectTo = route('portal.dashboard', ['company_id' => $company->id]);
        }
        return response()->json([
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => null,
            'data' => null,
            'redirect' => url($this->redirectTo),
        ]);
    }
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'status' => null,
            'success' => false,
            'error' => true,
            'message' => trans($response),
            'data' => null,
            'redirect' => null,
        ]);
    }
}
