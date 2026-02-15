<?php

namespace App\Domains\Execution\Services;

use App\Domains\Execution\Repositories\ExecutionRepository;
use App\Domains\Execution\Events\TaskCompleted;

class ExecutionService
{
    protected $repository;

    public function __construct(ExecutionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getExecutionSummary($projectId)
    {
        return [
            'tasks' => $this->repository->getTasksByProject($projectId),
            'milestones' => $this->repository->getMilestonesByProject($projectId),
        ];
    }

    public function completeTask($taskId)
    {
        $task = $this->repository->updateTaskProgress($taskId, 100);
        $task->update(['status' => 'Completed']);
        
        event(new TaskCompleted($task));

        return $task;
    }
}
