<?php
namespace App\Http\Requests\Common;
use App\Abstracts\Http\FormRequest;
class ItemTax extends FormRequest
{
    public function rules()
    {
        return [
            'item_id' => 'required|integer',
            'tax_id' => 'required|integer',
        ];
    }
}
