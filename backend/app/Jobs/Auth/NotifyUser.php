<?php
namespace App\Jobs\Auth;
use App\Abstracts\JobShouldQueue;
class NotifyUser extends JobShouldQueue
{
    protected $user;
    protected $notification;
    public function __construct($user, $notification)
    {
        $this->user = $user;
        $this->notification = $notification;
        $this->onQueue('jobs');
    }
    public function handle()
    {
        $this->user->notify($this->notification);
    }
}
