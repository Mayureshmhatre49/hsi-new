<?php

namespace App\Domains\Execution\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'status' => $this->status,
            'progress' => (int) $this->progress,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
