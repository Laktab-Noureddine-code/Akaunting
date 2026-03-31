<?php
namespace App\Http\Controllers\Common;
use App\Abstracts\Http\Controller;
use App\Models\Common\Contact;
class Contacts extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-sales-customers|read-purchases-vendors')->only('index');
    }
    public function index()
    {
        return response()->json([
            'success'   => true,
            'error'     => false,
            'data'      => Contact::collect(),
            'message'   => '',
        ]);
    }
}
