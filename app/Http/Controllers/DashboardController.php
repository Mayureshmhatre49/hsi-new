<?php

namespace App\Http\Controllers;

use App\Domains\Project\Models\Project;
use App\Domains\Finance\Services\FinanceService;
use App\Domains\AI\Services\AIService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $financeService;
    protected $aiService;

    public function __construct(FinanceService $financeService, AIService $aiService)
    {
        $this->financeService = $financeService;
        $this->aiService = $aiService;
    }

    public function index()
    {
        try {
            $projects = Project::latest()->take(5)->get();
            $metrics = $this->financeService->getPortfolioMetrics();
            $aiSummary = $this->aiService->getPortfolioSummary();
    
            return view('dashboard', compact('projects', 'metrics', 'aiSummary'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error loading dashboard: ' . $e->getMessage());
            // Return a view with limited data or error message if critical failure
            return view('dashboard', [
                'projects' => collect([]),
                'metrics' => ['total_capex' => 0, 'total_spend' => 0, 'avg_margin' => 0, 'utilization' => 0],
                'aiSummary' => ['stability_score' => 0, 'critical_risks' => [], 'alerts' => []]
            ])->withErrors(['error' => 'System telemetry unavailable. Displaying cached/empty state.']);
        }
    }

    public function portfolio()
    {
        try {
            $projects = Project::latest()->get();
            return view('projects.index', compact('projects'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to load portfolio.']);
        }
    }

    public function show(Project $project)
    {
        try {
            return view('projects.show', compact('project'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to load project details.']);
        }
    }

    public function boq()
    {
        try {
            return view('planning.boq');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to load BOQ workspace.']);
        }
    }
}
