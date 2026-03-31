<?php
namespace App\Events\Install;
use App\Abstracts\Event;
class UpdateCacheCleared extends Event
{
    public $company_id;
    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }
}
