<?php

namespace App\Domains\AI\Services;

use App\Domains\Project\Models\Project;
use App\Domains\Finance\Services\FinanceService;

class AIService
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function analyzeProjectRisk(Project $project)
    {
        $risks = [];

        // Check for budget overruns
        $overruns = $this->financeService->detectOverruns($project);
        foreach ($overruns as $overrun) {
            $risks[] = [
                'type' => 'Margin Alert',
                'title' => 'Critical Overrun: ' . $overrun['category'],
                'content' => sprintf(
                    'Financial breach detected. Actual spend ($%s) exceeds budget ($%s) by $%s (%.2f%%).',
                    number_format($overrun['actual'], 0),
                    number_format($overrun['allocated'], 0),
                    number_format($overrun['variance'], 0),
                    $overrun['percent']
                ),
                'severity' => 'high'
            ];
        }

        // Check for schedule delays
        $delayedTasks = $project->tasks()->where('status', 'Delayed')->count();
        if ($delayedTasks > 0) {
            $risks[] = [
                'type' => 'Schedule Risk',
                'title' => 'Task Delays Detected',
                'content' => "There are $delayedTasks tasks currently behind schedule.",
                'severity' => $delayedTasks > 2 ? 'high' : 'medium'
            ];
        }

        return $risks;
    }

    public function getPortfolioSummary()
    {
        $projects = Project::all();
        $allRisks = [];
        $lowestStability = 100;
        $totalStability = 0;

        foreach ($projects as $project) {
            if (!$project instanceof Project) continue;
            
            $risks = $this->analyzeProjectRisk($project);
            $allRisks = array_merge($allRisks, $risks);
            
            $projectStability = 100;
            $overruns = $this->financeService->detectOverruns($project);
            foreach ($overruns as $overrun) {
                $penalty = min(60, $overrun['percent'] / 1.5); // More aggressive penalty
                $projectStability -= $penalty;
            }

            $highRisksCount = count(array_filter($risks, fn($r) => $r['severity'] === 'high'));
            $projectStability -= ($highRisksCount * 30); // Higher penalty for high severity
            
            $projectStability = max(0, $projectStability);
            $totalStability += $projectStability;
            
            if ($projectStability < $lowestStability) {
                $lowestStability = $projectStability;
            }
        }

        // Use a blend of average and lowest to ensure visibility of outliers
        $avgStability = $projects->count() > 0 ? $totalStability / $projects->count() : 100;
        $finalScore = ($avgStability * 0.4) + ($lowestStability * 0.6);

        // Sort risks by severity
        usort($allRisks, function($a, $b) {
            $map = ['high' => 3, 'medium' => 2, 'low' => 1];
            return $map[$b['severity']] <=> $map[$a['severity']];
        });

        return [
            'stability_score' => round($finalScore),
            'critical_risks' => array_filter($allRisks, fn($r) => $r['severity'] === 'high'),
            'alerts' => array_slice($allRisks, 0, 8)
        ];
    }
}
