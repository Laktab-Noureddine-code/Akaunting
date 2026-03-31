<?php
namespace App\Events\Install;
use App\Abstracts\Event;
class UpdateFinished extends Event
{
    public $alias;
    public $new;
    public $old;
    public function __construct($alias, $new, $old)
    {
        $this->alias = $alias;
        $this->new = $new;
        $this->old = $old;
    }
}
