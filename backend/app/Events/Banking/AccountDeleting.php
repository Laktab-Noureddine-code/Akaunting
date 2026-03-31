<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class AccountDeleting extends Event
{
    public $account;
    public function __construct($account)
    {
        $this->account = $account;
    }
}
