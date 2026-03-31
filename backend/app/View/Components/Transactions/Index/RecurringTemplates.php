<?php
namespace App\View\Components\Transactions\Index;
use App\Abstracts\View\Components\Transactions\Index as Component;
class RecurringTemplates extends Component
{
    public function render()
    {
        return view('components.transactions.index.recurring_templates');
    }
}
