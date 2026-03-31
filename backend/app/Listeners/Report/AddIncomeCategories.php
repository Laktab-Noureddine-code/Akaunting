<?php
namespace App\Listeners\Report;
use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
class AddIncomeCategories extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
    ];
    public function handleFilterShowing(FilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }
        $event->class->filters['categories'] = $this->getIncomeCategories(true);
        $event->class->filters['routes']['categories'] = ['categories.index', 'search=type:income enabled:1'];
        $event->class->filters['multiple']['categories'] = true;
    }
    public function handleGroupShowing(GroupShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }
        $event->class->groups['category'] = trans_choice('general.categories', 1);
    }
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'category')) {
            return;
        }
        $all_categories = $this->getIncomeCategories();
        if ($category_ids = $this->getSearchStringValue('category_id')) {
            $categories = explode(',', $category_ids);
            $rows = collect($all_categories)->filter(function ($value, $key) use ($categories) {
                return in_array($key, $categories);
            });
        } else {
            $rows = $all_categories;
        }
        $this->setRowNamesAndValues($event, $rows);
        $event->class->row_tree_nodes = [];
        $nodes = $this->getCategoriesNodes($rows);
        $this->setTreeNodes($event, $nodes);
    }
}
