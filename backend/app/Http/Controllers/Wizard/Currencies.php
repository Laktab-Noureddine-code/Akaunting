<?php
namespace App\Http\Controllers\Wizard;
use Akaunting\Money\Currency as MoneyCurrency;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Currency as Request;
use App\Jobs\Setting\CreateCurrency;
use App\Jobs\Setting\DeleteCurrency;
use App\Jobs\Setting\UpdateCurrency;
use App\Models\Setting\Currency;
class Currencies extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-settings-currencies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-currencies')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-currencies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-currencies')->only('destroy');
    }
    public function index()
    {
        $currencies = Currency::collect();
        $current = Currency::orderBy('code')->pluck('code')->toArray();
        $codes = [];
        $money_currencies = MoneyCurrency::getCurrencies();
        foreach ($money_currencies as $key => $item) {
            $codes[$key] = $key;
        }
        return $this->response('wizard.currencies.index', compact('currencies', 'codes'));
    }
    public function show()
    {
        return redirect()->route('wizard.currencies.index');
    }
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateCurrency($request));
        if ($response['success']) {
            $message = trans('messages.success.created', ['type' => trans_choice('general.currencies', 1)]);
        } else {
            $message = $response['message'];
        }
        $response['message'] = $message;
        return response()->json($response);
    }
    public function update(Currency $currency, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateCurrency($currency, $request));
        $currency->default = default_currency() == $currency->code;
        if ($response['success']) {
            $message = trans('messages.success.updated', ['type' => $currency->name]);
        } else {
            $message = $response['message'];
        }
        $response['message'] = $message;
        return response()->json($response);
    }
    public function destroy(Currency $currency)
    {
        $currency_id = $currency->id;
        $response = $this->ajaxDispatch(new DeleteCurrency($currency));
        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $currency->name]);
        } else {
            $message = $response['message'];
        }
        $response['currency_id'] = $currency_id;
        $response['message'] = $message;
        return response()->json($response);
    }
}
