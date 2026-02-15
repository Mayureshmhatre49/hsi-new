<?php

namespace App\Domains\Procurement\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->po_number,
            'status' => $this->status,
            'total_amount' => (float) $this->total_amount,
            'vendor' => $this->whenLoaded('vendor'),
            'delivery_date' => $this->delivery_date,
        ];
    }
}
