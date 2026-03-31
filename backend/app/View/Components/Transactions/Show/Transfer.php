<?php
namespace App\View\Components\Transactions\Show;
use App\Abstracts\View\Components\Transactions\Show as Component;
class Transfer extends Component
{
    public function render()
    {
        return view('components.transactions.show.transfer');
    }
}
