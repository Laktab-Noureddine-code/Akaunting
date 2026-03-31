<?php
namespace App\Http\Controllers\Install;
use App\Http\Requests\Install\Setting as Request;
use App\Utilities\Installer;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
class Settings extends Controller
{
    public function create()
    {
        return view('install.settings.create');
    }
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $locale = session('locale') ?? config('app.locale');
            Installer::createCompany($request->get('company_name'), $request->get('company_email'), $locale);
            Installer::createUser($request->get('user_email'), $request->get('user_password'), $locale);
        });
        Installer::finalTouches();
        $response['redirect'] = route('login');
        return response()->json($response);
    }
}
