<?php

namespace App\Http\Controllers;

use App\Domains\Project\Models\Project;
use App\Domains\Project\Services\ProjectService;
use App\Domains\Project\Requests\CreateProjectRequest;
use App\Domains\Project\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;
    protected $financeService;
    protected $aiService;

    public function __construct(
        ProjectService $projectService,
        \App\Domains\Finance\Services\FinanceService $financeService,
        \App\Domains\AI\Services\AIService $aiService
    ) {
        $this->projectService = $projectService;
        $this->financeService = $financeService;
        $this->aiService = $aiService;
    }

    public function index()
    {
        try {
            $projects = $this->projectService->getAllProjects();
            $metrics = $this->financeService->getPortfolioMetrics();
            $aiSummary = $this->aiService->getPortfolioSummary();

            $kpis = [
                [
                    'label' => 'Total CapEx Deployed',
                    'val' => '$' . ($metrics['total_capex'] >= 1000000 ? number_format($metrics['total_capex'] / 1000000, 1) . 'M' : number_format($metrics['total_capex'] / 1000, 0) . 'K'),
                    'change' => '+0.0%', // Placeholder for historical comparison
                    'color' => 'blue'
                ],
                [
                    'label' => 'Avg. Margin Yield',
                    'val' => number_format($metrics['avg_margin'], 1) . '%',
                    'change' => 'Stable',
                    'color' => 'amber'
                ],
                [
                    'label' => 'Resource Utilization',
                    'val' => number_format($metrics['utilization'], 1) . '%',
                    'change' => 'Live',
                    'color' => 'green'
                ],
                [
                    'label' => 'Critical Risk Sites',
                    'val' => count($aiSummary['critical_risks']),
                    'change' => count($aiSummary['critical_risks']) > 0 ? 'Action Req' : 'Stable',
                    'color' => count($aiSummary['critical_risks']) > 0 ? 'red' : 'green'
                ],
            ];

            return view('projects.index', compact('projects', 'kpis'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching projects index: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Unable to load projects portfolio.']);
        }
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(CreateProjectRequest $request)
    {
        try {
            $project = $this->projectService->createProject($request->validated());
            return redirect()->route('projects.show', $project)->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating project: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create project. Please try again.']);
        }
    }

    public function show($id)
    {
        try {
            $project = $this->projectService->getProjectById($id);
            return view('projects.show', compact('project'));
        } catch (\Exception $e) {
            return redirect()->route('projects.index')->withErrors(['error' => 'Project not found.']);
        }
    }

    public function edit($id)
    {
        try {
            $project = $this->projectService->getProjectById($id);
            return view('projects.edit', compact('project'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to load project for editing.']);
        }
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        try {
            $this->projectService->updateProject($project, $request->validated());
            return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating project: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to update project.']);
        }
    }
}
