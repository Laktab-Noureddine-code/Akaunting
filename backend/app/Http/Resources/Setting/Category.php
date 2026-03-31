<?php
namespace App\Http\Resources\Setting;
use Illuminate\Http\Resources\Json\JsonResource;
class Category extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'type' => $this->type,
            'color' => $this->color,
            'enabled' => $this->enabled,
            'parent_id' => $this->parent_id,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
