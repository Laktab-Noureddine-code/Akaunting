<?php
namespace App\View\Components\Widgets;
use App\Abstracts\View\Component;
class LatestInvoices extends Component
{
    public $contact;
    public $model;
    public $invoices;
    public function __construct(
        $model = false, $invoices = false, $contact = false
    ) {
        $this->contact = ! empty($contact) ? $contact : user()->contact;
        $this->model = $model;
        $this->invoices = $this->getInvoices($model, $invoices);
    }
    public function render()
    {
        return view('components.widgets.latest_invoices');
    }
    protected function getInvoices($model, $invoices)
    {
        if (! empty($model)) {
            return $model;
        }
        if (! empty($invoices)) {
            return $invoices;
        }
        return $this->contact->invoices()->orderBy('created_at', 'desc')->limit(3)->get();
    }
}
