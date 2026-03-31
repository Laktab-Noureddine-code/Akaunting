<?php
namespace App\Http\Controllers\Common;
use Akaunting\Money\Currency as MoneyCurrency;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Company as Request;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;
use App\Traits\Uploads;
use App\Traits\Users;
class Companies extends Controller
{
    use Uploads, Users;
    public function __construct()
    {
        $this->middleware('permission:create-common-companies')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-common-companies')->only('show', 'edit', 'export');
        $this->middleware('permission:update-common-companies')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-common-companies')->only('destroy');
    }
    public function index()
    {
        $companies = user()->companies()->collect();
        return $this->response('common.companies.index', compact('companies'));
    }
    public function show()
    {
        return redirect()->route('companies.index');
    }
    public function create()
    {
        $money_currencies = MoneyCurrency::getCurrencies();
        $currencies = [];
        foreach ($money_currencies as $key => $item) {
            $currencies[$key] = $key . ' - ' . $item['name'];
        }
        return view('common.companies.create', compact('currencies'));
    }
    public function store(Request $request)
    {
        $current_company_id = company_id();
        $response = $this->ajaxDispatch(new CreateCompany($request));
        if ($response['success']) {
            $response['redirect'] = route('companies.switch', $response['data']->id);
            $message = trans('messages.success.created', ['type' => trans_choice('general.companies', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('companies.create');
            $message = $response['message'];
            flash($message)->error()->important();
        }
        company($current_company_id)->makeCurrent();
        return response()->json($response);
    }
    public function edit(Company $company)
    {
        if ($this->isNotUserCompany($company->id)) {
            return redirect()->route('companies.index');
        }
        $money_currencies = MoneyCurrency::getCurrencies();
        $currencies = [];
        foreach ($money_currencies as $key => $item) {
            $currencies[$key] = $key . ' - ' . $item['name'];
        }
        return view('common.companies.edit', compact('company', 'currencies'));
    }
    public function update(Company $company, Request $request)
    {
        $current_company_id = company_id();
        $response = $this->ajaxDispatch(new UpdateCompany($company, $request, company_id()));
        if ($response['success']) {
            $response['redirect'] = route('companies.index');
            $message = trans('messages.success.updated', ['type' => trans_choice('general.companies', 1)]);
            flash($message)->success();
        } else {
            $response['redirect'] = route('companies.edit', $company->id);
            $message = $response['message'];
            flash($message)->error()->important();
        }
        company($current_company_id)->makeCurrent();
        return response()->json($response);
    }
    public function enable(Company $company)
    {
        $response = $this->ajaxDispatch(new UpdateCompany($company, request()->merge(['enabled' => 1])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => trans_choice('general.companies', 1)]);
        }
        return response()->json($response);
    }
    public function disable(Company $company)
    {
        $response = $this->ajaxDispatch(new UpdateCompany($company, request()->merge(['enabled' => 0])));
        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => trans_choice('general.companies', 1)]);
        }
        return response()->json($response);
    }
    public function destroy(Company $company)
    {
        $response = $this->ajaxDispatch(new DeleteCompany($company));
        $response['redirect'] = route('companies.index');
        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.companies', 1)]);
            flash($message)->success();
        } else {
            $message = $response['message'];
            flash($message)->error()->important();
        }
        return response()->json($response);
    }
    public function switch(Company $company)
    {
        if ($this->isUserCompany($company->id)) {
            $old_company_id = company_id();
            $company->makeCurrent();
            session(['dashboard_id' => user()->dashboards()->enabled()->pluck('id')->first()]);
            event(new \App\Events\Common\CompanySwitched($company, $old_company_id));
            if (! setting('wizard.completed', false)) {
                return redirect()->route('wizard.edit', ['company_id' => $company->id]);
            }
        }
        return redirect()->route('dashboard', ['company_id' => $company->id]);
    }
    public function autocomplete()
    {
        $query = request('query');
        $autocomplete = Company::autocomplete([
            'name' => $query
        ]);
        $companies = $autocomplete->get()->sortBy('name')->pluck('name', 'id');
        return response()->json([
            'success' => true,
            'message' => 'Get all companies.',
            'errors' => [],
            'count' => $companies->count(),
            'data' => ($companies->count()) ? $companies : null,
        ]);
    }
}
