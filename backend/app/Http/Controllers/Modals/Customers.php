<?php
namespace App\Http\Controllers\Modals;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\UpdateContact;
class Customers extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-sales-customers')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-sales-customers')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-sales-customers')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-sales-customers')->only('destroy');
    }
    public function create()
    {
        $contact_selector = false;
        if (request()->has('contact_selector')) {
            $contact_selector = request()->get('contact_selector');
        }
        $html = view('modals.customers.create', compact('contact_selector'))->render();
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }
    public function store(Request $request)
    {
        $request['enabled'] = 1;
        $response = $this->ajaxDispatch(new CreateContact($request));
        if ($response['success']) {
            $response['message'] = trans('messages.success.created', ['type' => trans_choice('general.customers', 1)]);
        }
        return response()->json($response);
    }
    public function edit(Contact $customer)
    {
        $contact_selector = false;
        if (request()->has('contact_selector')) {
            $contact_selector = request()->get('contact_selector');
        }
        $html = view('modals.customers.edit', compact('customer', 'contact_selector'))->render();
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }
    public function update(Contact $customer, Request $request)
    {
        $request['enabled'] = 1;
        $response = $this->ajaxDispatch(new UpdateContact($customer, $request));
        if ($response['success']) {
            $response['message'] = trans('messages.success.updated', ['type' => trans_choice('general.customers', 1)]);
        }
        return response()->json($response);
    }
}
