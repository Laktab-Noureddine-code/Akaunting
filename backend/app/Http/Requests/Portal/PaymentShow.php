<?php
namespace App\Http\Requests\Portal;
use App\Abstracts\Http\FormRequest;
class PaymentShow extends FormRequest
{
    public function authorize()
    {
        if (auth()->guest()) {
            return true;
        }
        if (user()->can('read-banking-transactions')) {
            return true;
        }
        return $this->payment->contact_id == user()->contact->id;
    }
    public function rules()
    {
        return [
        ];
    }
}
