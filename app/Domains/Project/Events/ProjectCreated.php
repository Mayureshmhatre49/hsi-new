<?php

namespace App\Domains\Project\Events;

use App\Domains\Project\Models\Project;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Project $project) {}
}
