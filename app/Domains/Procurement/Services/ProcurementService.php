<?php

namespace App\Domains\Procurement\Services;

use App\Domains\Procurement\Repositories\ProcurementRepository;

class ProcurementService
{
    protected $repository;

    public function __construct(ProcurementRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getProcurementOverview()
    {
        return [
            'purchase_orders' => $this->repository->getAllPurchaseOrders(),
            'rfqs' => $this->repository->getAllRFQs(),
            'deliveries' => $this->repository->getAllDeliveries(),
        ];
    }
}
