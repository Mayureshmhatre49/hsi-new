<?php

namespace App\Domains\Project\Listeners;

use App\Domains\Project\Events\ProjectCreated;
use Illuminate\Support\Facades\Log;

class RecalculateMetrics
{
    public function handle(ProjectCreated $event): void
    {
        Log::info('Recalculating metrics for project: ' . $event->project->name);
        // Logic to initialize budgets, set default tasks, etc.
    }
}
