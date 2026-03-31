<?php
namespace App\Http\Requests\Setting;
use App\Abstracts\Http\FormRequest;
class EmailTemplate extends FormRequest
{
    public function rules()
    {
        return [
            'subject'   => 'required|string',
            'body'      => 'required|string',
        ];
    }
}
