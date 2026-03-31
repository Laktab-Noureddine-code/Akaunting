<?php
namespace App\Http\Requests\Install;
use App\Abstracts\Http\FormRequest;
class Database extends FormRequest
{
    public function rules()
    {
        return [
            'hostname' => 'required',
            'username' => 'required',
            'database' => 'required'
        ];
    }
}
