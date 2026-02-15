<?php

namespace App\Domains\Finance\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = ['project_id', 'category', 'allocated_amount', 'actual_spend'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
