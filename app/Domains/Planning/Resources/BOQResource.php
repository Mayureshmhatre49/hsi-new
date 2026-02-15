<?php

namespace App\Domains\Planning\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domains\Planning\Resources\BOQItemResource;

class BOQResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'version' => $this->version,
            'total_material' => (float) $this->total_material,
            'total_labor' => (float) $this->total_labor,
            'grand_total' => (float) $this->grand_total,
            'items' => BOQItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
