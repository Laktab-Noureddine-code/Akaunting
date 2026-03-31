<?php
namespace App\Http\Controllers\Purchases;
use App\Abstracts\Http\Controller;
use App\Exports\Purchases\Vendors as Export;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Imports\Purchases\Vendors as Import;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\DuplicateContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Contacts;
class Vendors extends Controller
{
    use Contacts;
    public $type = Contact::VENDOR_TYPE;
    public function index()
    {
        $vendors = Contact::with([
                'media',
                'bills.histories',
                'bills.totals',
                'bills.transactions',
                'bills.media'
            ])
            ->withCount([
                'contact_persons as contact_persons_with_email_count' => function ($query) {
                    $query->whereNotNull('email');
                }
            ])
            ->vendor()
            ->collect();
        return $this->response('purchases.vendors.index', compact('vendors'));
    }
    public function show(Contact $vendor)
    {
        return view('purchases.vendors.show', compact('vendor'));
    }
    public function create()
    {
        return view('purchases.vendors.create');
    }
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateContact($request));
        if ($response['success']) {
            $response['redirect'] = route('vendors.show', $response['data']->id);
            $message = trans('messages.success.created', ['type' => trans_choice('general.vendors', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('vendors.create');
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function duplicate(Contact $vendor)
    {
        $clone = $this->dispatch(new DuplicateContact($vendor));
        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.vendors', 1)]);
        flash($message)->success();
        return redirect()->route('vendors.edit', $clone->id);
    }
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.vendors', 2));
        if ($response['success']) {
            $response['redirect'] = route('vendors.index');
            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['purchases', 'vendors']);
            flash($response['message'])->error()->important();
        }
        return response()->json($response);
    }
    public function edit(Contact $vendor)
    {
        return view('purchases.vendors.edit', compact('vendor'));
    }
    public function update(Contact $vendor, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, $request));
        if ($response['success']) {
            $response['redirect'] = route('vendors.show', $response['data']->id);
            $message = trans('messages.success.updated', ['type' => $vendor->name]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('vendors.edit', $vendor->id);
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function enable(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, request()->merge(['enabled' => 1])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $vendor->name]);
        }
        return response()->json($response);
    }
    public function disable(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new UpdateContact($vendor, request()->merge(['enabled' => 0])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $vendor->name]);
        }
        return response()->json($response);
    }
    public function destroy(Contact $vendor)
    {
        $response = $this->ajaxDispatch(new DeleteContact($vendor));
        $response['redirect'] = route('vendors.index');
        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $vendor->name]);
            flash($message)->success();
        } else {
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.vendors', 2));
    }
    public function createBill(Contact $vendor)
    {
        $data['contact'] = $vendor;
        return redirect()->route('bills.create')->withInput($data);
    }
    public function createExpense(Contact $vendor)
    {
        $data['contact'] = $vendor;
        return redirect()->route('transactions.create', ['type' => 'expense'])->withInput($data);
    }
}
