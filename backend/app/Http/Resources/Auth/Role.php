<?php
namespace App\Http\Resources\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
class Role extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->display_name,
            'code' => $this->name,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
