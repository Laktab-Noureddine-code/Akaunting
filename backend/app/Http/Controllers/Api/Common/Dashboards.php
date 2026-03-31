<?php
namespace App\Http\Controllers\Api\Common;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Dashboard as Request;
use App\Http\Resources\Common\Dashboard as Resource;
use App\Jobs\Common\CreateDashboard;
use App\Jobs\Common\DeleteDashboard;
use App\Jobs\Common\UpdateDashboard;
use App\Models\Common\Dashboard;
use App\Traits\Users;
use Illuminate\Http\Response;
class Dashboards extends ApiController
{
    use Users;
    public function index()
    {
        $dashboards = user()->dashboards()->with('widgets')->collect();
        return Resource::collection($dashboards);
    }
    public function show($id)
    {
        try {
            $dashboard = Dashboard::with('widgets')->find($id);
            if (! $dashboard instanceof Dashboard) {
                return $this->errorInternal('No query results for model [' . Dashboard::class . '] ' . $id);
            }
            $this->canAccess($dashboard);
            return new Resource($dashboard);
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $dashboard = $this->dispatch(new CreateDashboard($request));
        return $this->created(route('api.dashboards.show', $dashboard->id), new Resource($dashboard));
    }
    public function update(Dashboard $dashboard, Request $request)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, $request));
            return new Resource($dashboard->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function enable(Dashboard $dashboard)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 1])));
            return new Resource($dashboard->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Dashboard $dashboard)
    {
        try {
            $dashboard = $this->dispatch(new UpdateDashboard($dashboard, request()->merge(['enabled' => 0])));
            return new Resource($dashboard->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Dashboard $dashboard)
    {
        try {
            $this->dispatch(new DeleteDashboard($dashboard));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function canAccess($dashboard)
    {
        if (!empty($dashboard) && $this->isUserDashboard($dashboard->id)) {
            return new Response('');
        }
        $message = trans('dashboards.error.not_user_dashboard');
        $this->errorUnauthorized($message);
    }
}
