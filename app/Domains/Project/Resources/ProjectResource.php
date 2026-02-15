<?php

namespace App\Domains\Project\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'client' => $this->client,
            'location' => $this->location,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'budget' => (float) $this->budget,
            'margin_projection' => (float) $this->margin_projection,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
