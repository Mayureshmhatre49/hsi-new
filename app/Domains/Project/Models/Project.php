<?php

namespace App\Domains\Project\Models;

use App\Domains\Planning\Models\BOQ;
use App\Domains\Execution\Models\Task;
use App\Domains\Execution\Models\Milestone;
use App\Domains\Finance\Models\Budget;
use App\Domains\Finance\Models\Expense;
use App\Domains\AI\Models\AIInsight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'client', 'location', 'start_date', 'end_date', 'status', 'budget', 'margin_projection'
    ];

    public function boqs()
    {
        return $this->hasMany(BOQ::class);
    }

    public function activeBoq()
    {
        return $this->hasOne(BOQ::class)->where('is_active', true);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function aiInsights()
    {
        return $this->hasMany(AIInsight::class);
    }
}
