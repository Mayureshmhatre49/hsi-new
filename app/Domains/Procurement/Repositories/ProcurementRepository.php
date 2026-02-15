<?php

namespace App\Domains\Procurement\Repositories;

use App\Domains\Procurement\Models\PurchaseOrder;
use App\Domains\Procurement\Models\RFQ;
use App\Domains\Procurement\Models\Delivery;

class ProcurementRepository
{
    public function getAllPurchaseOrders()
    {
        return PurchaseOrder::with(['vendor', 'project'])->latest()->get();
    }

    public function getAllRFQs()
    {
        return RFQ::with(['vendor', 'project'])->latest()->get();
    }

    public function getAllDeliveries()
    {
        return Delivery::with(['purchaseOrder', 'project'])->latest()->get();
    }
}
