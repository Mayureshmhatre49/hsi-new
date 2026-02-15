<?php

namespace Database\Seeders;

use App\Domains\User\Models\Role;
use App\Domains\User\Models\User;
use App\Domains\Project\Models\Project;
use App\Domains\Planning\Models\BOQ;
use App\Domains\Planning\Models\BOQItem;
use App\Domains\Vendor\Models\Vendor;
use App\Domains\Execution\Models\Task;
use App\Domains\Execution\Models\Milestone;
use App\Domains\Finance\Models\Budget;
use App\Domains\Finance\Models\Expense;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Project Manager', 'slug' => 'project_manager'],
            ['name' => 'Procurement Manager', 'slug' => 'procurement_manager'],
            ['name' => 'Finance Manager', 'slug' => 'finance_manager'],
            ['name' => 'Site Supervisor', 'slug' => 'site_supervisor'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }

        // Admin User
        $admin = User::updateOrCreate(
            ['email' => 'alex@hsi.com'],
            [
                'name' => 'Alex Morgan',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'admin')->first()->id,
            ]
        );

        // Projects
        $projects = [
            [
                'name' => 'Tower 42 Interior',
                'client' => 'City Corp',
                'location' => 'London, UK',
                'start_date' => '2023-01-01',
                'end_date' => '2023-12-31',
                'status' => 'On Track',
                'budget' => 5000000.00,
                'margin_projection' => 15.5
            ],
            [
                'name' => 'HQ Expansion Ph.2',
                'client' => 'Tech Giant',
                'location' => 'Austin, TX',
                'start_date' => '2023-06-01',
                'end_date' => '2024-06-01',
                'status' => 'Execution',
                'budget' => 2500000.00,
                'margin_projection' => 18.2
            ]
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Budget
            Budget::create([
                'project_id' => $project->id,
                'category' => 'Materials',
                'allocated_amount' => $project->budget * 0.5,
                'actual_spend' => $project->budget * 0.2
            ]);
            Budget::create([
                'project_id' => $project->id,
                'category' => 'Labor',
                'allocated_amount' => $project->budget * 0.3,
                'actual_spend' => $project->budget * 0.15
            ]);

            // Tasks
            Task::create([
                'project_id' => $project->id,
                'name' => 'Site Mobilization',
                'start_date' => $project->start_date,
                'end_date' => '2023-02-01',
                'progress' => 100,
                'status' => 'Done',
                'phase' => 'Phase 1'
            ]);
        }

        // Vendors
        Vendor::create(['name' => 'Herman Miller Inc.', 'type' => 'Global Partner', 'score' => 98]);
        Vendor::create(['name' => 'Steelcase Office', 'type' => 'Preferred Vendor', 'score' => 92]);
    }
}
