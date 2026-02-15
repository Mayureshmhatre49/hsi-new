<?php

namespace App\Domains\Project\Repositories;

use App\Domains\Project\Models\Project;

class ProjectRepository
{
    public function all()
    {
        return Project::with(['activeBoq', 'tasks', 'milestones'])->get();
    }

    public function find($id)
    {
        return Project::with(['boqs.items', 'tasks', 'milestones', 'budgets', 'expenses', 'aiInsights'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Project::create($data);
    }
}
