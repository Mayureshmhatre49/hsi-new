<?php

namespace App\Domains\Planning\Repositories;

use App\Domains\Planning\Models\BOQ;
use App\Domains\Planning\Models\BOQItem;

class PlanningRepository
{
    public function getActiveBoqByProject($projectId)
    {
        return BOQ::where('project_id', $projectId)->where('status', 'Active')->first();
    }

    public function createBoq(array $data)
    {
        return BOQ::create($data);
    }

    public function findBoq($id)
    {
        return BOQ::with('items')->findOrFail($id);
    }
}
