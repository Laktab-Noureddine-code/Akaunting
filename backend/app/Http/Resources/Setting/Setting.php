<?php
namespace App\Http\Resources\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
class Setting extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
