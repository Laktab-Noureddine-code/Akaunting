<?php
namespace App\View\Components\Layouts\Admin;
use App\Abstracts\View\Component;
use App\Events\Menu\NotificationsCreated;
class Menu extends Component
{
    public $companies = [];
    public $notification_count;
    public function render()
    {
        $this->companies = $this->getCompanies();
        $this->notification_count = $this->getNotificationCount();
        return view('components.layouts.admin.menu');
    }
    public function getCompanies()
    {
        $companies = [];
        if ($user = user()) {
            $companies = $user->companies()->enabled()->limit(10)->get()->sortBy('name');
        }
        return $companies;
    }
    public function getNotificationCount()
    {
        $notifications = new \stdClass();
        $notifications->notifications = collect();
        $notifications->keyword = '';
        event(new NotificationsCreated($notifications));
        return $notifications->notifications->count();
    }
}
