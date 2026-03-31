<?php
namespace App\Http\Requests\Document;
use App\Abstracts\Http\FormRequest;
class DocumentHistory extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required|string',
            'document_id' => 'required|integer',
            'status' => 'required|string',
            'notify' => 'required|integer',
        ];
    }
}
