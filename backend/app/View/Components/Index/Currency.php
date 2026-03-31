<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
class Currency extends Component
{
    public $currency;
    public $code;
    public function __construct($code) {
        $this->code = $code;
    }
    public function render()
    {
        $code = ($this->code) ? $this->code : default_currency();
        $this->currency = currency($code)->getName();
        return view('components.index.currency');
    }
}
