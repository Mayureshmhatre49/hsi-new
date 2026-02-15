<?php

namespace App\Domains\Planning\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class BOQ extends Model
{
    protected $table = 'boqs';

    protected $fillable = [
        'project_id', 'version', 'is_active', 'status', 'total_material', 'total_labor', 'grand_total'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(BOQItem::class, 'boq_id');
    }
}
