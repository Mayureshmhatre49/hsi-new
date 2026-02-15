<?php

namespace App\Domains\Planning\Models;

use Illuminate\Database\Eloquent\Model;

class BOQItem extends Model
{
    protected $fillable = [
        'boq_id', 'code', 'description', 'unit', 'quantity', 'unit_rate', 'material_cost', 'labor_cost', 'total', 'group_name'
    ];

    public function boq()
    {
        return $this->belongsTo(BOQ::class, 'boq_id');
    }
}
