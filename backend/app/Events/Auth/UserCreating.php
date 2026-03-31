<?php
namespace App\Events\Auth;
use Illuminate\Queue\SerializesModels;
class UserCreating
{
    use SerializesModels;
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
}
