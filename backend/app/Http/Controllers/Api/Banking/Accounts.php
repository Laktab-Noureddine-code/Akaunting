<?php
namespace App\Http\Controllers\Api\Banking;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Account as Request;
use App\Http\Resources\Banking\Account as Resource;
use App\Jobs\Banking\CreateAccount;
use App\Jobs\Banking\DeleteAccount;
use App\Jobs\Banking\UpdateAccount;
use App\Models\Banking\Account;
class Accounts extends ApiController
{
    public function index()
    {
        $accounts = Account::collect();
        return Resource::collection($accounts);
    }
    public function show($id)
    {
        if (is_numeric($id)) {
            $account = Account::find($id);
        } else {
            $account = Account::where('number', $id)->first();
        }
        if (! $account instanceof Account) {
            return $this->errorInternal('No query results for model [' . Account::class . '] ' . $id);
        }
        return new Resource($account);
    }
    public function store(Request $request)
    {
        $account = $this->dispatch(new CreateAccount($request));
        return $this->created(route('api.accounts.show', $account->id), new Resource($account));
    }
    public function update(Account $account, Request $request)
    {
        try {
            $account = $this->dispatch(new UpdateAccount($account, $request));
            return new Resource($account->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function enable(Account $account)
    {
        try {
            $account = $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));
            return new Resource($account->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Account $account)
    {
        try {
            $account = $this->dispatch(new UpdateAccount($account, request()->merge(['enabled' => 0])));
            return new Resource($account->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Account $account)
    {
        try {
            $this->dispatch(new DeleteAccount($account));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
