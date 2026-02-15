<?php

namespace App\Domains\Finance\Events;

use App\Domains\Project\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BudgetUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Project $project) {}
}
