<?php

namespace App\Domains\Execution\Models;

use App\Domains\Project\Models\Project;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    protected $fillable = ['project_id', 'user_id', 'content', 'photo_path'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
