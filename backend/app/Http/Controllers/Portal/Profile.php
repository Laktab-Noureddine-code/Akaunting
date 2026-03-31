<?php
namespace App\Http\Controllers\Portal;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Portal\Profile as Request;
use App\Traits\Uploads;
class Profile extends Controller
{
    use Uploads;
    public function index()
    {
        return $this->edit();
    }
    public function show()
    {
        return $this->edit();
    }
    public function edit()
    {
        $user = user();
        return view('portal.profile.edit', compact('user'));
    }
    public function update($user_id, Request $request)
    {
        $user = user();
        if (empty($request['password'])) {
            unset($request['current_password']);
            unset($request['password']);
            unset($request['password_confirmation']);
        }
        $user->update($request->input());
        if ($request->file('picture')) {
            $media = $this->getMedia($request->file('picture'), 'users');
            $user->attachMedia($media, 'picture');
        }
        $user->contact->update($request->input());
        $message = trans('messages.success.updated', ['type' => trans('auth.profile')]);
        flash($message)->success();
        $response = [
            'success' => true,
            'error' => false,
            'data' => null,
            'message' => '',
            'redirect' => route('portal.profile.edit', $user->id),
        ];
        return response()->json($response);
    }
    public function readOverdueInvoices()
    {
        $user = user();
        foreach ($user->unreadNotifications as $notification) {
            if ($notification->getAttribute('type') != 'App\Notifications\Sale\Invoice') {
                continue;
            }
            $notification->markAsRead();
        }
        return redirect()->route('portal.invoices.index');
    }
}
