<?php

namespace App\Domains\Finance\Services;

use App\Domains\Project\Models\Project;
use App\Domains\Finance\Repositories\FinanceRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceService
{
    protected $repository;

    public function __construct(FinanceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getBurnRate(Project $project)
    {
        $totalSpend = $project->expenses()->sum('amount');
        $startDate = Carbon::parse($project->start_date);
        $now = Carbon::now();

        $monthsPassed = $startDate->diffInMonths($now);
        if ($monthsPassed <= 0) $monthsPassed = 1;

        return $totalSpend / $monthsPassed;
    }

    public function detectOverruns(Project $project)
    {
        $overruns = [];
        
        // Check total project budget vs total expenses
        $totalSpend = $project->expenses()->sum('amount');
        if ($totalSpend > $project->budget && $project->budget > 0) {
            $overruns[] = [
                'category' => 'Total Project Scope',
                'allocated' => $project->budget,
                'actual' => $totalSpend,
                'variance' => $totalSpend - $project->budget,
                'percent' => (($totalSpend / $project->budget) - 1) * 100
            ];
        }

        // Check category-specific budgets
        $budgets = $this->repository->getBudgetsByProject($project->id);
        foreach ($budgets as $budget) {
            $actualCategorySpend = $project->expenses()->where('category', $budget->category)->sum('amount');
            if ($actualCategorySpend > $budget->allocated_amount) {
                $overruns[] = [
                    'category' => $budget->category,
                    'allocated' => $budget->allocated_amount,
                    'actual' => $actualCategorySpend,
                    'variance' => $actualCategorySpend - $budget->allocated_amount,
                    'percent' => (($actualCategorySpend / $budget->allocated_amount) - 1) * 100
                ];
            }
        }

        return $overruns;
    }

    public function getCashflowForecast(Project $project)
    {
        $burnRate = $this->getBurnRate($project);
        $totalExpenses = $project->expenses()->sum('amount');
        $remainingBudget = $project->budget - $totalExpenses;

        $monthsRemaining = $remainingBudget > 0 && $burnRate > 0 ? $remainingBudget / $burnRate : 0;

        return [
            'monthly_burn' => $burnRate,
            'remaining_budget' => $remainingBudget,
            'projected_completion_months' => $monthsRemaining,
            'total_at_completion' => $totalExpenses + $remainingBudget
        ];
    }

    public function getPortfolioMetrics()
    {
        $projects = Project::all();
        $totalBudget = $projects->sum('budget');
        $totalSpend = 0;
        $totalMargin = $projects->avg('margin_projection') ?? 0;

        foreach ($projects as $project) {
            $totalSpend += $project->expenses()->sum('amount');
        }

        return [
            'total_capex' => $totalBudget,
            'total_spend' => $totalSpend,
            'avg_margin' => $totalMargin,
            'utilization' => $totalBudget > 0 ? ($totalSpend / $totalBudget) * 100 : 0
        ];
    }

    public function addExpense(array $data)
    {
        return DB::transaction(function () use ($data) {
            $expense = $this->repository->createExpense([
                'project_id' => $data['project_id'],
                'amount' => $data['amount'],
                'category' => $data['category'],
                'description' => $data['description'] ?? 'Manual expense entry',
                'transaction_date' => now(),
                'status' => 'Paid'
            ]);

            // Dispatch event to trigger AI analysis
            event(new \App\Domains\Finance\Events\BudgetUpdated($expense->project));

            return $expense;
        });
    }
}
