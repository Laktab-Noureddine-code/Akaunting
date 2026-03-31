<?php
namespace App\Http\Requests\Common;
use App\Abstracts\Http\FormRequest;
class BulkAction extends FormRequest
{
    public function rules()
    {
        return [
            'handle' => 'required|string',
            'selected' => 'required',
        ];
    }
}
