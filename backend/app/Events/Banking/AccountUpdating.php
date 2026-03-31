<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
use App\Models\Banking\Account;
class AccountUpdating extends Event
{
    public $account;
    public $request;
    public function __construct(Account $account, $request)
    {
        $this->account = $account;
        $this->request  = $request;
    }
}
