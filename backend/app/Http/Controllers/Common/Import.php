<?php
namespace App\Http\Controllers\Common;
use App\Abstracts\Http\Controller;
use App\Traits\Import as ImportTrait;
class Import extends Controller
{
    use ImportTrait;
    public function create($group, $type, $route = null)
    {
        list($view, $data) = $this->getImportView($group, $type, $route);
        return view($view, $data);
    }
}
