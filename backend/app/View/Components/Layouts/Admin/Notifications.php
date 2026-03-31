<?php
namespace App\View\Components\Layouts\Admin;
use App\Abstracts\View\Component;
use App\Traits\Modules;
use Illuminate\Support\Facades\Route;
class Notifications extends Component
{
    use Modules;
    public $notifications;
    public function render()
    {
        $this->notifications = $this->getNotify();
        return view('components.layouts.admin.notifications');
    }
    public function getNotify()
    {
        if (! $path = Route::current()->uri()) {
            return [];
        }
        $path = str_replace('{company_id}/', '', $path);
        $path = str_replace('{company_id}', '', $path);
        $notify = [];
        $notifications = $this->getNotifications($path);
        foreach ($notifications as $notification) {
            $path = str_replace('/', '
            $message = str_replace('
            $message = str_replace('
            $message = str_replace('
            $message = str_replace('
            if (! setting('notifications.' . $notification->path . '.' . $notification->id . '.status', 1)) {
                continue;
            }
            $notify[] = $message;
        }
        return $notify;
    }
}
