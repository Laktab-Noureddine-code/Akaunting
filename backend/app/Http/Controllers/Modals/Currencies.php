<?php
namespace App\Http\Controllers\Modals;
use Akaunting\Money\Currency as MoneyCurrency;
use App\Abstracts\Http\Controller;
use App\Jobs\Setting\CreateCurrency;
use App\Models\Setting\Currency;
use App\Http\Requests\Setting\Currency as Request;
class Currencies extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-settings-currencies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-currencies')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-currencies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-currencies')->only('destroy');
    }
    public function create()
    {
        $current = Currency::pluck('code')->toArray();
        $codes = [];
        $currencies = MoneyCurrency::getCurrencies();
        foreach ($currencies as $key => $item) {
            if (in_array($key, $current)) {
                continue;
            }
            $codes[$key] = $key;
        }
        $html = view('modals.currencies.create', compact('codes'))->render();
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }
    public function store(Request $request)
    {
        $currency = currency($request->get('code'));
        $request['precision'] = (int) $currency->getPrecision();
        $request['symbol'] = $currency->getSymbol();
        $request['symbol_first'] = $currency->isSymbolFirst() ? 1 : 0;
        $request['decimal_mark'] = $currency->getDecimalMark();
        $request['thousands_separator'] = $currency->getThousandsSeparator();
        $request['enabled'] = 1;
        $request['default_currency'] = false;
        $response = $this->ajaxDispatch(new CreateCurrency($request->all()));
        if ($response['success']) {
            $response['message'] = trans('messages.success.created', ['type' => trans_choice('general.currencies', 1)]);
        }
        return response()->json($response);
    }
}
