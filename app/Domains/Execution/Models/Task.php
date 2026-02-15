<?php

namespace App\Domains\Execution\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id', 'name', 'start_date', 'end_date', 'progress', 'status', 'phase', 'zone'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
