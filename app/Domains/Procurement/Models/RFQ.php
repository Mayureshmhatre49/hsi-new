<?php

namespace App\Domains\Procurement\Models;

use App\Domains\Project\Models\Project;
use Illuminate\Database\Eloquent\Model;

class RFQ extends Model
{
    protected $table = 'rfqs';

    protected $fillable = ['project_id', 'rfq_number', 'title', 'status', 'closing_date'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
