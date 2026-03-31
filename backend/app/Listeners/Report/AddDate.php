<?php
namespace App\Listeners\Report;
use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
class AddDate extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
        'App\Reports\ProfitLoss',
        'App\Reports\TaxSummary',
        'App\Reports\DiscountSummary',
    ];
    public function handleFilterShowing(FilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }
        $this->setDateFilter($event);
    }
    public function handleFilterApplying(FilterApplying $event)
    {
        if (empty($event->args['date_field'])) {
            return;
        }
        $this->applyDateFilter($event);
    }
}
