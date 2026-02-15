<?php

namespace App\Domains\AI\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class RiskFlag extends Model
{
    protected $fillable = ['project_id', 'target_type', 'target_id', 'reason', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
