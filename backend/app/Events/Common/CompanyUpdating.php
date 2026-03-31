<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class CompanyUpdating extends Event
{
    public $company;
    public $request;
    public function __construct($company, $request)
    {
        $this->company = $company;
        $this->request = $request;
    }
}
