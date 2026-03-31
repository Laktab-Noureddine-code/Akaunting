<?php
namespace App\Http\Requests\Document;
use App\Abstracts\Http\FormRequest;
class DocumentAddItem extends FormRequest
{
    public function rules()
    {
        return [
            'item_row' => 'required|integer',
            'currency_code' => 'required|string|currency',
        ];
    }
}
