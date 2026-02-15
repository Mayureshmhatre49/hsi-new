<?php

namespace App\Http\Controllers;

use App\Domains\Procurement\Services\ProcurementService;

class ProcurementController extends Controller
{
    protected $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }

    public function index()
    {
        $data = $this->procurementService->getProcurementOverview();
        return view('procurement.index', $data);
    }
}
