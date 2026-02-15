<?php

namespace App\Domains\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Finance\Services\FinanceService;
use App\Domains\Project\Models\Project;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    protected $service;

    public function __construct(FinanceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $projects = Project::with('expenses', 'budgets')->get();
        $metrics = $this->service->getPortfolioMetrics();
        
        return view('finance.index', compact('projects', 'metrics'));
    }

    public function getDashboard(Project $project)
    {
        try {
            return response()->json([
                'burn_rate' => $this->service->getBurnRate($project),
                'overruns' => $this->service->detectOverruns($project),
                'forecast' => $this->service->getCashflowForecast($project),
                'total_budget' => $project->budget,
                'actual_spend' => $project->expenses()->sum('amount')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to calculate financial metrics.'], 500);
        }
    }

    public function storeExpense(Request $request, Project $project)
    {
        try {
            $data = $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'category' => 'required|string',
                'description' => 'nullable|string',
            ]);
    
            $data['project_id'] = $project->id;
            
            $this->service->addExpense($data);
    
            return back()->with('success', 'Expense recorded successfully and AI analysis triggered.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Expense recording failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to record expense.']);
        }
    }
}
