<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\SessionAuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\CopilotController;
use App\Domains\Finance\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [SessionAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [SessionAuthController::class, 'login']);
Route::post('/logout', [SessionAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

    Route::get('/planning/boq', [DashboardController::class, 'boq'])->name('planning.boq');
    Route::get('/procurement', [ProcurementController::class, 'index'])->name('procurement.index');
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/projects/{project}/expenses', [FinanceController::class, 'storeExpense'])->name('projects.expenses.store');

    Route::post('/copilot/chat', [CopilotController::class, 'chat'])->name('copilot.chat');
});
