<?php
namespace App\Http\Requests\Common;
use App\Abstracts\Http\FormRequest;
class Dashboard extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'users' => 'required|array',
        ];
    }
}
