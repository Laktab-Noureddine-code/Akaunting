<?php
namespace App\View\Components;
use Illuminate\Support\Str;
use App\Abstracts\View\Components\Form as BaseForm;
class Pagination extends BaseForm
{
    public $items;
    public $limits;
    public function __construct(
        $items,
        $limits = [],
    ) {
        $this->items = $items;
        $this->limits = $this->getLimits($limits);
    }
    public function render()
    {
        return view('components.pagination');
    }
    protected function getLimits($limits)
    {
        if (! empty($limits)) {
            return $limits;
        }
        $limits = [
            '10' => '10',
            '25' => '25',
            '50' => '50',
            '100' => '100'
        ];
        return $limits;
    }
}
