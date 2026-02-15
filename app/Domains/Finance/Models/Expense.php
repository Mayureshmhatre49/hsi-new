<?php

namespace App\Domains\Finance\Models;

use App\Domains\Project\Models\Project;
use App\Domains\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['project_id', 'vendor_id', 'description', 'amount', 'category', 'transaction_date', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
