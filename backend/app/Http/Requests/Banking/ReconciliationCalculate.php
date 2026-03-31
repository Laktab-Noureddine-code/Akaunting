<?php
namespace App\Http\Requests\Banking;
use App\Abstracts\Http\FormRequest;
class ReconciliationCalculate extends FormRequest
{
    public function rules()
    {
        return [
            'currency_code' => 'required|string|currency',
            'closing_balance' => 'required',
            'transactions' => 'required',
        ];
    }
}
