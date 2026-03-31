<?php
namespace App\Http\Requests\Common;
use App\Abstracts\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
class ReportShow extends FormRequest
{
    public function rules()
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        if ($validator->errors()->has('start_date') && $validator->errors()->has('end_date')) {
            request()->query->remove('start_date');
            request()->query->remove('end_date');
            return;
        }
        if ($validator->errors()->has('start_date')) {
            request()->merge([
                'start_date'   => request('end_date'),
            ]);
            return;
        }
        if ($validator->errors()->has('end_date')) {
            request()->merge([
                'end_date'   => request('start_date'),
            ]);
            return;
        }
    }
}
