<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
class Country extends Component
{
    public $country;
    public $code;
    public function __construct($code) {
        $this->code = $code;
        $this->country = trans('general.na');
    }
    public function render()
    {
        if (! empty($this->code) && array_key_exists($this->code, trans('countries'))) {
            $this->country = trans('countries.' . $this->code);
        }
        return view('components.index.country');
    }
}
