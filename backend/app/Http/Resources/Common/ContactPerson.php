<?php
namespace App\Http\Resources\Common;
use Illuminate\Http\Resources\Json\JsonResource;
class ContactPerson extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'contact_id' => $this->contact_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
