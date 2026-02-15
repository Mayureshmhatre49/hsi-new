<?php

namespace App\Domains\Execution\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = ['project_id', 'name', 'due_date', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
