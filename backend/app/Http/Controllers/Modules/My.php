<?php
namespace App\Http\Controllers\Modules;
use App\Abstracts\Http\Controller;
use App\Traits\Modules;
class My extends Controller
{
    use Modules;
    public function index()
    {
        $purchase = $this->getMyModules(['query' => ['limit' => 16]]);
        $installed = $this->getInstalledModules();
        return $this->response('modules.my.index', compact('purchase', 'installed'));
    }
}
