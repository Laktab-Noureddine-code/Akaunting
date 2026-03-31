<?php
namespace App\Http\Requests\Common;
use Illuminate\Foundation\Http\FormRequest;
class Import extends FormRequest
{
    public function rules()
    {
        return [
            'import' => 'required|file|extension:' . config('excel.imports.extensions'),
        ];
    }
}
