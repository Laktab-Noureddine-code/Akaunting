<?php
namespace App\Http\Controllers\Wizard;
use App\Abstracts\Http\Controller;
use App\Traits\Modules;
class Finish extends Controller
{
    use Modules;
    public function __construct()
    {
        $this->middleware('permission:read-admin-panel')->only('index', 'show', 'edit', 'export');
    }
    public function index()
    {
        setting()->set('wizard.completed', 1);
        setting()->save();
        $data = [
            'query' => [
                'limit' => 6
            ]
        ];
        $modules = $this->getFeaturedModules($data);
        return $this->response('wizard.finish.index', compact('modules'));
    }
    public function update()
    {
        setting()->set('wizard.completed', 1);
        setting()->save();
        return response()->json([]);
    }
}
