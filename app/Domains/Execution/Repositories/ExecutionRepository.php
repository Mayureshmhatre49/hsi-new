<?php

namespace App\Domains\Execution\Repositories;

use App\Domains\Execution\Models\Task;
use App\Domains\Execution\Models\Milestone;
use App\Domains\Execution\Models\DailyLog;

class ExecutionRepository
{
    public function getTasksByProject($projectId)
    {
        return Task::where('project_id', $projectId)->get();
    }

    public function getMilestonesByProject($projectId)
    {
        return Milestone::where('project_id', $projectId)->get();
    }

    public function createDailyLog(array $data)
    {
        return DailyLog::create($data);
    }

    public function updateTaskProgress($taskId, $progress)
    {
        $task = Task::findOrFail($taskId);
        $task->update(['progress' => $progress]);
        return $task;
    }
}
