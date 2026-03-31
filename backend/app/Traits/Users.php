<?php
namespace App\Traits;
use App\Models\Auth\UserInvitation;
use Illuminate\Support\Facades\Route;
trait Users
{
    public function getCurrentUser()
    {
        return user();
    }
    public function isUserCompany($id)
    {
        $user = user();
        if (empty($user)) {
            return app()->runningInConsole() ? true : false;
        }
        $company = $user->withoutEvents(function () use ($user, $id) {
            return $user->companies()->where('id', $id)->first();
        });
        return !empty($company);
    }
    public function isNotUserCompany($id)
    {
        return !$this->isUserCompany($id);
    }
    public function isUserDashboard($id)
    {
        $user = user();
        if (empty($user)) {
            return app()->runningInConsole() ? true : false;
        }
        $dashboard = $user->withoutEvents(function () use ($user, $id) {
            return $user->dashboards()->where('id', $id)->first();
        });
        return !empty($dashboard);
    }
    public function isNotUserDashboard($id)
    {
        return !$this->isUserDashboard($id);
    }
    public function getFirstCompanyOfUser()
    {
        $user = user();
        if (empty($user)) {
            return null;
        }
        $company = $user->withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });
        if (empty($company)) {
            return null;
        }
        return $company;
    }
    public function getLandingPageOfUser()
    {
        $user = user();
        if (empty($user)) {
            return route('login');
        }
        $route_name = $user->isCustomer()
                    ? 'portal.dashboard'
                    : (Route::has($user->landing_page) ? $user->landing_page : 'dashboard');
        $company_id = company_id() ?: $this->getFirstCompanyOfUser()?->id;
        if (empty($company_id)) {
            return route('login');
        }
        return route($route_name, ['company_id' => $company_id]);
    }
    public function hasPendingInvitation()
    {
        return $this->getPendingInvitation() ? true : false;
    }
    public function getPendingInvitation()
    {
        return UserInvitation::where('user_id', $this->id)->first();
    }
}
