<?php

namespace App\Domains\AI\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\AI\Services\AIService;
use App\Domains\Project\Models\Project;

class AIController extends Controller
{
    protected $service;

    public function __construct(AIService $service)
    {
        $this->service = $service;
    }

    public function getInsights(Project $project)
    {
        $insights = $this->service->analyzeProjectRisk($project);
        return response()->json($insights);
    }
}
