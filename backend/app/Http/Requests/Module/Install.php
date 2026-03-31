<?php
namespace App\Http\Requests\Module;
use App\Abstracts\Http\FormRequest;
class Install extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'alias' => 'alpha_dash',
            'version' => 'nullable|regex:/^[a-z0-9.]+$/i',
            'installed' => 'nullable|regex:/^[a-z0-9.]+$/i',
            'path' => 'nullable|string',
        ];
    }
}
