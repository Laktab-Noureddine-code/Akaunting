<?php
namespace App\Http\Controllers\Modules;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Module\Module as Request;
use App\Traits\Plans;
class ApiKey extends Controller
{
    use Plans;
    public function create()
    {
        return view('modules.api_key.create');
    }
    public function store(Request $request)
    {
        setting()->set('apps.api_key', $request['api_key']);
        setting()->save();
        $this->clearPlansCache();
        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('apps.home.index'),
            'message' => '',
        ]);
    }
}
