<?php
namespace App\Http\Controllers\Auth;
use App\Abstracts\Http\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\Auth\Forgot as Request;
use Illuminate\Support\Facades\Password;
class Forgot extends Controller
{
    use SendsPasswordResetEmails;
    protected $redirectTo = 'auth/forgot';
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function create()
    {
        return view('auth.forgot.create');
    }
    public function store(Request $request)
    {
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
    protected function sendResetLinkResponse($response)
    {
        $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => trans('passwords.sent'),
            'data' => null,
            'redirect' => null,
        ];
        return response()->json($response);
    }
    protected function sendResetLinkFailedResponse($response)
    {
        $response = [
            'status' => null,
            'success' => false,
            'error' => true,
            'message' => trans('passwords.user'),
            'data' => null,
            'redirect' => route('forgot'),
        ];
        return response()->json($response);
    }
}
