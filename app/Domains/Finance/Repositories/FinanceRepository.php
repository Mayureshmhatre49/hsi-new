<?php

namespace App\Domains\Finance\Repositories;

use App\Domains\Finance\Models\Budget;
use App\Domains\Finance\Models\Expense;

class FinanceRepository
{
    public function getBudgetsByProject($projectId)
    {
        return Budget::where('project_id', $projectId)->get();
    }

    public function getExpensesByProject($projectId)
    {
        return Expense::where('project_id', $projectId)->latest()->get();
    }

    public function createBudget(array $data)
    {
        return Budget::create($data);
    }

    public function createExpense(array $data)
    {
        return Expense::create($data);
    }
}
