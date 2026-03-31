<?php
namespace App\Http\Controllers\Sales;
use App\Abstracts\Http\Controller;
use App\Exports\Sales\Customers as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Sales\Customers as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\DuplicateContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Contacts;
class Customers extends Controller
{
    use Contacts;
    public $type = Contact::CUSTOMER_TYPE;
    public function index()
    {
        $customers = Contact::customer()
            ->with([
                'media',
                'invoices.histories',
                'invoices.totals',
                'invoices.transactions',
                'invoices.media'
            ])
            ->withCount([
                'contact_persons as contact_persons_with_email_count' => function ($query) {
                    $query->whereNotNull('email');
                }
            ])
            ->collect();
        return $this->response('sales.customers.index', compact('customers'));
    }
    public function show(Contact $customer)
    {
        return view('sales.customers.show', compact('customer'));
    }
    public function create()
    {
        return view('sales.customers.create');
    }
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateContact($request));
        if ($response['success']) {
            $response['redirect'] = route('customers.show', $response['data']->id);
            $message = trans('messages.success.created', ['type' => trans_choice('general.customers', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.create');
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function duplicate(Contact $customer)
    {
        $clone = $this->dispatch(new DuplicateContact($customer));
        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.customers', 1)]);
        flash($message)->success();
        return redirect()->route('customers.edit', $clone->id);
    }
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.customers', 2));
        if ($response['success']) {
            $response['redirect'] = route('customers.index');
            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['sales', 'customers']);
            flash($response['message'])->error()->important();
        }
        return response()->json($response);
    }
    public function edit(Contact $customer)
    {
        return view('sales.customers.edit', compact('customer'));
    }
    public function update(Contact $customer, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, $request));
        if ($response['success']) {
            $response['redirect'] = route('customers.show', $response['data']->id);
            $message = trans('messages.success.updated', ['type' => $customer->name]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('customers.edit', $customer->id);
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function enable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 1])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $customer->name]);
        }
        return response()->json($response);
    }
    public function disable(Contact $customer)
    {
        $response = $this->ajaxDispatch(new UpdateContact($customer, request()->merge(['enabled' => 0])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $customer->name]);
        }
        return response()->json($response);
    }
    public function destroy(Contact $customer)
    {
        $response = $this->ajaxDispatch(new DeleteContact($customer));
        $response['redirect'] = route('customers.index');
        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $customer->name]);
            flash($message)->success();
        } else {
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.customers', 2));
    }
    public function createInvoice(Contact $customer)
    {
        $data['contact'] = $customer;
        return redirect()->route('invoices.create')->withInput($data);
    }
    public function createIncome(Contact $customer)
    {
        $data['contact'] = $customer;
        return redirect()->route('transactions.create', ['type' => 'income'])->withInput($data);
    }
}
