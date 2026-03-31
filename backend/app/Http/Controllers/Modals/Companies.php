<?php
namespace App\Http\Controllers\Modals;
use App\Abstracts\Http\Controller;
use App\Models\Common\Company;
class Companies extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-settings-company')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-company')->only('update', 'enable', 'disable');
    }
    public function edit(Company $company)
    {
        $html = view('modals.companies.edit', compact('company'))->render();
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }
}
