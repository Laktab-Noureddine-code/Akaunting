<?php
namespace App\Http\Controllers\Api\Auth;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Auth\User as Request;
use App\Http\Resources\Auth\User as Resource;
use App\Jobs\Auth\CreateUser;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
class Users extends ApiController
{
    public function index()
    {
        $users = user_model_class()::with('companies', 'media', 'permissions', 'roles')->isNotCustomer()->collect();
        return Resource::collection($users);
    }
    public function show($id)
    {
        $model_class = user_model_class();
        if (is_numeric($id)) {
            $user = $model_class::with('companies', 'permissions', 'roles')->find($id);
        } else {
            $user = $model_class::with('companies', 'permissions', 'roles')->where('email', $id)->first();
        }
        if (! $user instanceof $model_class) {
            return $this->errorInternal('No query results for model [' . $model_class . '] ' . $id);
        }
        return new Resource($user);
    }
    public function store(Request $request)
    {
        $user = $this->dispatch(new CreateUser($request));
        return $this->created(route('api.users.show', $user->id), new Resource($user));
    }
    public function update($user_id, Request $request)
    {
        try {
            $user = user_model_class()::query()->isNotCustomer()->find($user_id);
            $user = $this->dispatch(new UpdateUser($user, $request));
            return new Resource($user->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function enable($user_id)
    {
        try {
            $user = user_model_class()::query()->isNotCustomer()->find($user_id);
            $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 1])));
            return new Resource($user->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable($user_id)
    {
        try {
            $user = user_model_class()::query()->isNotCustomer()->find($user_id);
            $user = $this->dispatch(new UpdateUser($user, request()->merge(['enabled' => 0])));
            return new Resource($user->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy($user_id)
    {
        $user = user_model_class()::query()->isNotCustomer()->find($user_id);
        try {
            $this->dispatch(new DeleteUser($user));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
