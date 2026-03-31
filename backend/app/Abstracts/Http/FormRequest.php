<?php
namespace App\Abstracts\Http;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
abstract class FormRequest extends BaseFormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => company_id(),
        ]);
    }
}
