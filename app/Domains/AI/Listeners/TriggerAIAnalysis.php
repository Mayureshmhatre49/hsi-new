<?php

namespace App\Domains\AI\Listeners;

use App\Domains\Finance\Events\BudgetUpdated;
use App\Domains\AI\Services\AIService;
use Illuminate\Support\Facades\Log;

class TriggerAIAnalysis
{
    public function __construct(protected AIService $aiService) {}

    public function handle(BudgetUpdated $event): void
    {
        Log::info('Triggering AI Analysis for project: ' . $event->project->name);
        $this->aiService->analyzeProjectRisk($event->project);
    }
}
