@extends('layouts.app')

@section('title', 'Finance Intelligence - HSI Smart Execution System™')
@section('page_title', 'Portfolio Financial Control')

@section('content')
<div class="space-y-6">
    <!-- Financial KPI Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Portfolio CapEx</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">${{ number_format($metrics['total_capex'], 0) }}</h3>
            <div class="mt-4 h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-primary" style="width: 100%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Actual Spend (Utilized)</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">${{ number_format($metrics['total_spend'], 0) }}</h3>
            <div class="mt-4 h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-blue-400" style="width: {{ $metrics['utilization'] }}%"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Avg. Projected Margin</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($metrics['avg_margin'], 1) }}%</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded uppercase">On Track</span>
            </div>
        </div>
    </div>

    <!-- Project Finance Matrix -->
    <div class="bg-white dark:bg-[#1a2634] rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card overflow-hidden">
        <div class="p-6 border-b border-neutral-border dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Project Financial Health</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Project</th>
                        <th class="px-6 py-4">Total Budget</th>
                        <th class="px-6 py-4">Actual Spend</th>
                        <th class="px-6 py-4">Utilization</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($projects as $project)
                    @php
                        $spend = $project->expenses->sum('amount');
                        $util = $project->budget > 0 ? ($spend / $project->budget) * 100 : 0;
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $project->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-400">${{ number_format($project->budget, 0) }}</td>
                        <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-400">${{ number_format($spend, 0) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary" style="width: {{ $util }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">{{ round($util) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest {{ $util > 90 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                {{ $util > 90 ? 'Alert' : 'Stable' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
