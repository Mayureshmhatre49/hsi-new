<?php

namespace App\Domains\Project\Services;

use App\Domains\Project\Models\Project;
use App\Domains\Project\Repositories\ProjectRepository;
use App\Domains\Project\Events\ProjectCreated;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    protected $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllProjects()
    {
        return $this->repository->all();
    }

    public function getProjectById($id)
    {
        return $this->repository->find($id);
    }

    public function createProject(array $data)
    {
        return DB::transaction(function () use ($data) {
            $project = $this->repository->create([
                'name' => $data['name'],
                'client' => $data['client'],
                'location' => $data['location'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'budget' => $data['budget'],
                'margin_projection' => $data['margin_projection'],
                'status' => 'Planning',
            ]);

            event(new ProjectCreated($project));

            return $project;
        });
    }

    public function updateProject(Project $project, array $data)
    {
        return DB::transaction(function () use ($project, $data) {
            $project->update($data);
            return $project;
        });
    }
}
