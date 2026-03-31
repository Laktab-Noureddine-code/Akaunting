<?php
namespace App\Http\Requests\Auth;
use Illuminate\Foundation\Http\FormRequest;
class Register extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'token' => 'required|uuid|string|max:255',
            'password' => 'required|string|confirmed',
        ];
    }
}
