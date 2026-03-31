<?php
namespace App\Http\Resources\Document;
use App\Http\Resources\Document\DocumentItemTax;
use Illuminate\Http\Resources\Json\JsonResource;
class DocumentItem extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'document_id' => $this->document_id,
            'item_id' => $this->item_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'price_formatted' => money($this->price, $this->document->currency_code)->format(),
            'total' => $this->total,
            'total_formatted' => money($this->total, $this->document->currency_code)->format(),
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'taxes' => [static::$wrap => DocumentItemTax::collection($this->taxes)],
        ];
    }
}
