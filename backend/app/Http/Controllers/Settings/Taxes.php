<?php
namespace App\Http\Controllers\Settings;
use App\Abstracts\Http\Controller;
use App\Exports\Settings\Taxes as Export;
use App\Http\Requests\Common\Import as ImportRequest;
use App\Http\Requests\Setting\Tax as Request;
use App\Imports\Settings\Taxes as Import;
use App\Jobs\Setting\CreateTax;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;
class Taxes extends Controller
{
    public function index()
    {
        $taxes = Tax::collect();
        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];
        return $this->response('settings.taxes.index', compact('taxes', 'types'));
    }
    public function show()
    {
        return redirect()->route('taxes.index');
    }
    public function create()
    {
        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];
        $disable_options = [];
        if ($compound = Tax::compound()->first()) {
            $disable_options = ['compound'];
        }
        return view('settings.taxes.create', compact('types', 'disable_options'));
    }
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateTax($request));
        if ($response['success']) {
            $response['redirect'] = route('taxes.index');
            $message = trans('messages.success.created', ['type' => trans_choice('general.taxes', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('taxes.create');
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('general.taxes', 2));
        if ($response['success']) {
            $response['redirect'] = route('taxes.index');
            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['settings', 'taxes']);
            flash($response['message'])->error()->important();
        }
        return response()->json($response);
    }
    public function edit(Tax $tax)
    {
        $types = [
            'fixed' => trans('taxes.fixed'),
            'normal' => trans('taxes.normal'),
            'inclusive' => trans('taxes.inclusive'),
            'withholding' => trans('taxes.withholding'),
            'compound' => trans('taxes.compound'),
        ];
        $disable_options = [];
        if ($tax->type != 'compound' && $compound = Tax::compound()->first()) {
            $disable_options = ['compound'];
        }
        return view('settings.taxes.edit', compact('tax', 'types', 'disable_options'));
    }
    public function update(Tax $tax, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, $request));
        if ($response['success']) {
            $response['redirect'] = route('taxes.index');
            $message = trans('messages.success.updated', ['type' => $tax->name]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('taxes.edit', $tax->id);
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function enable(Tax $tax)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, request()->merge(['enabled' => 1])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $tax->name]);
        }
        return response()->json($response);
    }
    public function disable(Tax $tax)
    {
        $response = $this->ajaxDispatch(new UpdateTax($tax, request()->merge(['enabled' => 0])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $tax->name]);
        }
        return response()->json($response);
    }
    public function destroy(Tax $tax)
    {
        $response = $this->ajaxDispatch(new DeleteTax($tax));
        $response['redirect'] = route('taxes.index');
        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $tax->name]);
            flash($message)->success();
        } else {
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function export()
    {
        return $this->exportExcel(new Export, trans_choice('general.taxes', 2));
    }
}
