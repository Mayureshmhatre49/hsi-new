<?php

namespace App\Domains\Planning\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Planning\Repositories\PlanningRepository;
use App\Domains\Planning\Services\PlanningService;
use App\Domains\Planning\Requests\BOQRequest;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(PlanningRepository $repository, PlanningService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getActiveBoq($projectId)
    {
        $boq = $this->repository->getActiveBoq($projectId);
        return response()->json($boq);
    }

    public function store(BOQRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $boq = $this->repository->createBoq($data);
        $this->repository->addItems($boq, $items);
        $this->service->calculateTotals($boq);

        return response()->json($boq->load('items'), 201);
    }

    public function simulate(Request $request)
    {
        $request->validate([
            'total_cost' => 'required|numeric',
            'target_margin' => 'required|numeric'
        ]);

        $result = $this->service->simulateMargin($request->total_cost, $request->target_margin);
        return response()->json($result);
    }
}
