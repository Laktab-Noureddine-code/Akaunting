<?php
namespace App\Http\Requests\Portal;
use App\Abstracts\Http\FormRequest;
class InvoiceShow extends FormRequest
{
    public function authorize()
    {
        if (auth()->guest()) {
            return true;
        }
        if (user()->can('read-sales-invoices')) {
            return true;
        }
        return $this->invoice->contact_id == user()->contact->id;
    }
    public function rules()
    {
        return [
        ];
    }
}
