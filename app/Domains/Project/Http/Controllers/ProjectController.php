<?php

namespace App\Domains\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Project\Repositories\ProjectRepository;
use App\Domains\Project\Requests\ProjectRequest;
use App\Domains\Project\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $projects = $this->repository->all();
        return ProjectResource::collection($projects);
    }

    public function show($id)
    {
        $project = $this->repository->find($id);
        return new ProjectResource($project);
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->repository->create($request->validated());
        return new ProjectResource($project);
    }
}
