<?php
namespace App\Http\Controllers\Modules;
use App\Abstracts\Http\Controller;
class Home extends Controller
{
    public function index()
    {
        return $this->response('modules.home.index');
    }
    public function show()
    {
        return redirect()->route('apps.home.index');
    }
}
