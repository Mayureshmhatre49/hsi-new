<?php

namespace App\Domains\Procurement\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['purchase_order_id', 'tracking_number', 'expected_date', 'actual_date', 'status'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
