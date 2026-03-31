<?php
namespace App\Http\Controllers\Api\Common;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Company as Request;
use App\Http\Resources\Common\Company as Resource;
use App\Jobs\Common\CreateCompany;
use App\Jobs\Common\DeleteCompany;
use App\Jobs\Common\UpdateCompany;
use App\Models\Common\Company;
use App\Traits\Users;
use Illuminate\Http\Response;
class Companies extends ApiController
{
    use Users;
    public function index()
    {
        $companies = user()->companies()->collect();
        return Resource::collection($companies);
    }
    public function show(Company $company)
    {
        try {
            $this->canAccess($company);
            return new Resource($company);
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $company = $this->dispatch(new CreateCompany($request));
        return $this->created(route('api.companies.show', $company->id), new Resource($company));
    }
    public function update(Company $company, Request $request)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, $request));
            return new Resource($company->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function enable(Company $company)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, request()->merge(['enabled' => 1])));
            return new Resource($company->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Company $company)
    {
        try {
            $company = $this->dispatch(new UpdateCompany($company, request()->merge(['enabled' => 0])));
            return new Resource($company->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Company $company)
    {
        try {
            $this->dispatch(new DeleteCompany($company));
            return $this->noContent();
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function canAccess(Company $company)
    {
        if (! empty($company) && $this->isUserCompany($company->id)) {
            return new Response('');
        }
        $message = trans('companies.error.not_user_company');
        $this->errorUnauthorized($message);
    }
}
