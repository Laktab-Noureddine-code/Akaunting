<?php
namespace App\View\Components\Widgets;
use App\Abstracts\View\Component;
class PaymentHistory extends Component
{
    public $contact;
    public $model;
    public $payments;
    public function __construct(
        $model = false, $payments = false, $contact = false
    ) {
        $this->contact = ! empty($contact) ? $contact : user()->contact;
        $this->model = $model;
        $this->payments = $this->getPayment($model, $payments);
    }
    public function render()
    {
        return view('components.widgets.payment_history');
    }
    protected function getPayment($model, $payment)
    {
        if (! empty($model)) {
            return $model;
        }
        if (! empty($payment)) {
            return $payment;
        }
        return $this->contact->income_transactions()->orderBy('created_at', 'desc')->limit(3)->get();
    }
}
