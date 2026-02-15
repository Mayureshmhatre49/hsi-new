<?php

namespace App\Domains\Finance\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'allocated' => (float) $this->allocated_amount,
            'actual' => (float) $this->actual_spend,
            'variance' => (float) ($this->allocated_amount - $this->actual_spend),
        ];
    }
}
