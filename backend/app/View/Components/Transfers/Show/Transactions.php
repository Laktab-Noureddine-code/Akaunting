<?php
namespace App\View\Components\Transfers\Show;
use App\Abstracts\View\Components\Transfers\Show as Component;
class Transactions extends Component
{
    public function render()
    {
        return view('components.transfers.show.transactions');
    }
}
