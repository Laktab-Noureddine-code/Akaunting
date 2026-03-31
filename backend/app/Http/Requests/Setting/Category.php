<?php
namespace App\Http\Requests\Setting;
use App\Abstracts\Http\FormRequest;
class Category extends FormRequest
{
    public function rules()
    {
        $types = collect(config('type.category'))->keys();
        return [
            'name' => 'required|string',
            'type' => 'required|string|in:' . $types->implode(','),
            'color' => 'required|string|colour',
        ];
    }
}
