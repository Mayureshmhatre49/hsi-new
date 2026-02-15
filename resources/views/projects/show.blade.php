@extends('layouts.app')

@section('title', $project->name . ' - HSI Smart Execution System™')
@section('page_title', 'Project Intelligence')

@section('content')
<div class="space-y-8">
    <!-- Project Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/20">
                    <span class="material-icons text-2xl">architecture</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $project->name }}</h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $project->location }}</span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-xs font-bold text-primary uppercase tracking-widest">Active Execution</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="px-6 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                Project Vault
            </button>
            <button class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                Execution Settings
            </button>
        </div>
    </div>

    <!-- Execution Timeline (Gantt-style) -->
    <div class="bg-white dark:bg-[#1a2634] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest flex items-center gap-2">
                <span class="material-icons text-primary text-sm">reorder</span>
                Execution Timeline
            </h3>
            <div class="flex gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-primary"></span> Completed</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Active</span>
            </div>
        </div>
        <div class="p-6 space-y-6">
            @if($project->tasks->count() > 0)
                @foreach($project->tasks as $t)
                <div class="group">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $t->name }}</span>
                        <span class="text-[10px] font-bold text-slate-400 tracking-wide uppercase">{{ $t->status }} — {{ $t->progress_percentage }}%</span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden relative">
                        <div class="h-full bg-{{ $t->progress_percentage == 100 ? 'primary' : ($t->progress_percentage > 0 ? 'amber-400' : 'slate-200') }} rounded-full group-hover:bg-blue-600 transition-all duration-500" style="width:{{ $t->progress_percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No active tasks initialized for this block.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- New: Record Expense Card -->
            <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest">Record Actual Cost</h3>
                    <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded uppercase">AI Trigger</span>
                </div>
                <form action="{{ route('projects.expenses.store', $project) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Amount ($)</label>
                        <input type="number" name="amount" step="0.01" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-primary outline-none" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Category</label>
                        <select name="category" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-primary outline-none">
                            <option>Material</option>
                            <option>Labor</option>
                            <option>Equipment</option>
                            <option>Permits</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2 bg-primary text-white text-[10px] font-bold uppercase tracking-widest rounded-lg hover:shadow-lg hover:shadow-primary/20 transition-all">Record & Analyze</button>
                    </div>
                </form>
            </div>

            <!-- Project Progress -->
            <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-6">Financial Telemetry</h3>
                <div class="flex gap-12">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Approved Budget</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white font-mono">${{ number_format($project->budget, 0) }}</p>
                    </div>
                    <div>
                        @php $actualSpend = $project->expenses->sum('amount'); @endphp
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Actual Spend</p>
                        <p class="text-2xl font-bold text-primary font-mono">${{ number_format($actualSpend, 0) }}</p>
                    </div>
                </div>
                <div class="mt-8">
                    @php
                        $util = $project->budget > 0 ? ($actualSpend / $project->budget) * 100 : 0;
                        $matPercent = $actualSpend > 0 ? ($project->expenses->where('category', 'Material')->sum('amount') / $actualSpend) * 100 : 0;
                        $labPercent = $actualSpend > 0 ? ($project->expenses->where('category', 'Labor')->sum('amount') / $actualSpend) * 100 : 0;
                        $othPercent = 100 - ($matPercent + $labPercent);
                    @endphp
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Expense Distribution (Utilization: {{ round($util) }}%)</p>
                    <div class="flex h-3 gap-1 overflow-hidden rounded-full">
                        <div class="h-full bg-primary" style="width:{{ $matPercent }}%"></div>
                        <div class="h-full bg-blue-400" style="width:{{ $labPercent }}%"></div>
                        <div class="h-full bg-slate-200 dark:bg-slate-700" style="width:{{ $othPercent }}%"></div>
                    </div>
                    <div class="flex justify-between mt-3 text-[10px] font-bold uppercase text-slate-400">
                        <span>Materials ({{ round($matPercent) }}%)</span>
                        <span>Labor ({{ round($labPercent) }}%)</span>
                        <span>Other ({{ round($othPercent) }}%)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card">
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-6">Critical Paths</h3>
                <div class="space-y-4">
                    <div class="p-3 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl">
                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest mb-1">Supply Blockage</p>
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Custom fixtures delayed at port.</p>
                    </div>
                    <div class="p-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-xl">
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Labor Capacity</p>
                        <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Skilled welders allocation at 95%.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
