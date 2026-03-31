<?php
namespace App\Events\Module;
use App\Abstracts\Event;
class Installed extends Event
{
    public $alias;
    public $company_id;
    public $locale;
    public function __construct($alias, $company_id, $locale)
    {
        $this->alias = $alias;
        $this->company_id = $company_id;
        $this->locale = $locale;
    }
}
