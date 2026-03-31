<?php
namespace App\Http\Requests\Banking;
use App\Abstracts\Http\FormRequest;
class Transfer extends FormRequest
{
    public function rules()
    {
        return [
            'from_account_id' => 'required|integer',
            'to_account_id' => 'required|integer',
            'amount' => 'required|amount',
            'transferred_at' => 'required|date_format:Y-m-d',
            'payment_method' => 'required|string|payment_method',
        ];
    }
}
