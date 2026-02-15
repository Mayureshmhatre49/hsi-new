<?php

namespace App\Domains\Procurement\Models;

use App\Domains\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['rfq_id', 'vendor_id', 'po_number', 'total_amount', 'status'];

    public function rfq()
    {
        return $this->belongsTo(RFQ::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }
}
