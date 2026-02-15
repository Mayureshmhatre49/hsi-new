<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\User\Http\Controllers\AuthController;
use App\Domains\Project\Http\Controllers\ProjectController;
use App\Domains\Planning\Http\Controllers\PlanningController;
use App\Domains\Finance\Http\Controllers\FinanceController;
use App\Domains\AI\Http\Controllers\AIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('role');
    });

    // Projects
    Route::apiResource('projects', ProjectController::class);

    // Planning (Only Admin, PM, Procurement can access)
    Route::middleware('role:admin,project_manager,procurement_manager')->group(function () {
        Route::get('projects/{project}/boq/active', [PlanningController::class, 'getActiveBoq']);
        Route::post('boqs', [PlanningController::class, 'store']);
        Route::post('planning/simulate', [PlanningController::class, 'simulate']);
    });

    // Finance
    Route::get('projects/{project}/finance/dashboard', [FinanceController::class, 'getDashboard']);

    // AI
    Route::get('projects/{project}/ai/insights', [AIController::class, 'getInsights']);
});
