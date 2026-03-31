<?php
namespace App\Http\Requests\Common;
use App\Abstracts\Http\FormRequest;
class Notification extends FormRequest
{
    public function rules()
    {
        return [
            'path' => 'required|string',
            'id' => 'required|integer',
        ];
    }
}
