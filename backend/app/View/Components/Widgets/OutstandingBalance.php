<?php
namespace App\View\Components\Widgets;
use App\Abstracts\View\Component;
use Illuminate\Support\Str;
class OutstandingBalance extends Component
{
    public $contact;
    public function __construct(
        $contact = false
    ) {
        $this->contact = ! empty($contact) ? $contact : user()->contact;
    }
    public function render()
    {
        return view('components.widgets.outstanding_balance');
    }
}
