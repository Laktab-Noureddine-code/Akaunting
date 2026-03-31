<?php
namespace App\Http\Requests\Install;
use App\Abstracts\Http\FormRequest;
class Setting extends FormRequest
{
    public function rules()
    {
        return [
            'company_name' => 'required',
            'company_email' => 'required|email',
            'user_email' => 'required|email',
            'user_password' => 'required'
        ];
    }
}
