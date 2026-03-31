<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class CompanyMakingCurrent extends Event
{
    public $company;
    public function __construct($company)
    {
        $this->company = $company;
    }
}
