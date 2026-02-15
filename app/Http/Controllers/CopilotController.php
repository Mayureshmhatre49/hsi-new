<?php

namespace App\Http\Controllers;

use App\Domains\AI\Services\AIService;
use App\Domains\Project\Models\Project;
use Illuminate\Http\Request;

class CopilotController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $message = $request->message;
        $projectId = $request->project_id;
        
        // Contextual awareness: Try to find project name in the message
        $allProjects = Project::all();
        $mentionedProject = null;
        foreach ($allProjects as $proj) {
            if (stripos($message, $proj->name) !== false) {
                $mentionedProject = $proj;
                break;
            }
        }

        $project = $mentionedProject ?: ($projectId ? Project::find($projectId) : Project::latest()->first());
        
        if (!$project) {
            return response()->json([
                'reply' => "I couldn't find any active projects to analyze. Protocol standby.",
                'insights' => []
            ]);
        }

        $risks = $this->aiService->analyzeProjectRisk($project);
        
        $criticalCount = count(array_filter($risks, fn($r) => $r['severity'] === 'high'));
        $totalCount = count($risks);

        if ($totalCount === 0) {
            $reply = "I've analyzed the telemetry for {$project->name}. No critical anomalies detected in the current execution block.";
        } elseif ($totalCount === 1) {
            $reply = "I've analyzed {$project->name}. " . $risks[0]['content'];
        } else {
            $reply = "I've analyzed {$project->name} and detected $totalCount operational risks. ";
            if ($criticalCount > 0) {
                $reply .= "CRITICAL: $criticalCount high-severity anomalies require immediate intervention.";
            } else {
                $reply .= "Review the identified risks to maintain project stability.";
            }
        }

        return response()->json([
            'reply' => $reply,
            'insights' => $risks,
            'project_name' => $project->name
        ]);
    }
}
