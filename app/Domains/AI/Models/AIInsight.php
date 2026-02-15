<?php

namespace App\Domains\AI\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class AIInsight extends Model
{
    protected $table = 'ai_insights';

    protected $fillable = ['project_id', 'type', 'title', 'content', 'severity'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
