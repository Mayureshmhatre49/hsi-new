@extends('layouts.app')

@section('title', 'Dashboard - HSI Smart Execution System™')
@section('page_title', 'Executive Portfolio Overview')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-soft">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back, {{ auth()->user()->name }}</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Portfolio performance is <span class="text-green-600 font-bold">+{{ number_format($metrics['avg_margin'], 1) }}%</span> above baseline this month.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-lg hover:bg-slate-100 transition-all uppercase tracking-widest">Download Report</button>
            <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-lg shadow-lg shadow-primary/20 hover:scale-105 transition-all uppercase tracking-widest">New Project</a>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Projects -->
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-primary">
                    <span class="material-icons">architecture</span>
                </div>
                <span class="text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded uppercase">Active</span>
            </div>
            <h4 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $projects->count() }}</h4>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Managed Assets</p>
        </div>

        <!-- Health Score -->
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-500">
                    <span class="material-icons">health_and_safety</span>
                </div>
                <span class="text-xs font-bold text-slate-400">{{ $aiSummary['stability_score'] }}/100</span>
            </div>
            <h4 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $aiSummary['stability_score'] > 80 ? 'Optimal' : ($aiSummary['stability_score'] > 60 ? 'Stable' : 'Risk') }}</h4>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Portfolio Health Index</p>
        </div>

        <!-- CapEx Deployed -->
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500">
                    <span class="material-icons">account_balance_wallet</span>
                </div>
                @php
                    $spend = $metrics['total_spend'];
                    $spendLabel = $spend >= 1000000 ? number_format($spend / 1000000, 1) . 'M' : number_format($spend / 1000, 0) . 'K';
                @endphp
                <span class="text-xs font-bold text-slate-400">${{ $spendLabel }}</span>
            </div>
            @php
                $capex = $metrics['total_capex'];
                $capexLabel = $capex >= 1000000 ? number_format($capex / 1000000, 1) . 'M' : number_format($capex / 1000, 0) . 'K';
            @endphp
            <h4 class="text-2xl font-bold text-slate-900 dark:text-white">${{ $capexLabel }}</h4>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Total CapEx Committed</p>
        </div>

        <!-- Margin Projection -->
        <div class="bg-primary p-6 rounded-2xl border border-primary/20 shadow-lg shadow-primary/20">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                    <span class="material-icons">payments</span>
                </div>
                <span class="text-[10px] font-bold text-white/80 uppercase tracking-widest">Aggregate</span>
            </div>
            <h4 class="text-2xl font-bold text-white">{{ number_format($metrics['avg_margin'], 1) }}%</h4>
            <p class="text-xs font-bold text-white/70 uppercase tracking-widest mt-1">Net Portfolio Margin</p>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Stats -->
        <div class="lg:col-span-2 bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Financial Performance</h3>
                    <p class="text-xs text-slate-500 font-medium">Budget allocation vs. Actual spend across projects</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Live Telemetry</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                </div>
            </div>
            
            <div class="space-y-6">
                @foreach($projects as $project)
                @php
                    $actual = $project->expenses->sum('amount');
                    $utilization = $project->budget > 0 ? ($actual / $project->budget) * 100 : 0;
                    $isOverrun = $utilization > 100;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest">{{ $project->name }}</span>
                        <div class="flex gap-2">
                            <span class="text-xs font-bold text-slate-400">{{ number_format($project->budget / 1000, 0) }}K</span>
                            @if($isOverrun)
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-tighter animate-pulse">! OVERRUN</span>
                            @endif
                        </div>
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full {{ $isOverrun ? 'bg-red-500' : 'bg-gradient-to-r from-primary to-blue-400' }} rounded-full" style="width: {{ min(100, $utilization) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between">
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Allocated</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-200"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Utilized</span>
                    </div>
                </div>
                <a href="{{ route('projects.index') }}" class="text-[10px] font-bold text-primary uppercase hover:underline">Portfolio Detail</a>
            </div>
        </div>

        <!-- Risk Insight -->
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card flex flex-col">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight mb-1">AI Intelligence</h3>
            <p class="text-xs text-slate-500 font-medium mb-6">Real-time risk telemetry</p>
            
            <div class="flex-1 flex flex-col items-center justify-center py-6">
                <!-- Circular Risk Meter -->
                <div class="relative w-32 h-32 flex items-center justify-center mb-6">
                    <svg class="w-full h-full -rotate-90">
                        <circle cx="64" cy="64" r="58" fill="transparent" stroke="currentColor" stroke-width="8" class="text-slate-100 dark:text-slate-800"></circle>
                        <circle cx="64" cy="64" r="58" fill="transparent" stroke="currentColor" stroke-width="8" stroke-dasharray="364.4" stroke-dashoffset="{{ 364.4 * (1 - ($aiSummary['stability_score'] / 100)) }}" class="text-primary transition-all duration-1000"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $aiSummary['stability_score'] }}%</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase">Stability</span>
                    </div>
                </div>

                <div class="w-full space-y-3 overflow-y-auto max-h-48 scrollbar-hide">
                    @if(isset($aiSummary['alerts']) && count($aiSummary['alerts']) > 0)
                        @foreach($aiSummary['alerts'] as $alert)
                        <div class="p-3 {{ $alert['severity'] === 'high' ? 'bg-red-50 dark:bg-red-900/10 border-red-100 dark:border-red-900/30' : 'bg-amber-50 dark:bg-amber-900/10 border-amber-100 dark:border-amber-900/30' }} border rounded-xl">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-icons {{ $alert['severity'] === 'high' ? 'text-red-500' : 'text-amber-500' }} text-sm">{{ $alert['severity'] === 'high' ? 'error' : 'warning' }}</span>
                                <span class="text-[10px] font-bold {{ $alert['severity'] === 'high' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }} uppercase">{{ $alert['type'] }}</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-400">{{ $alert['content'] }}</p>
                        </div>
                        @endforeach
                    @else
                        <div class="p-3 bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-xl">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-icons text-green-500 text-sm">check_circle</span>
                                <span class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase">Healthy</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-400">All systems operating within established baselines.</p>
                        </div>
                    @endif
                </div>
            </div>

            <button @click="copilotOpen = true" class="w-full py-2.5 bg-slate-900 dark:bg-slate-800 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-slate-800 dark:hover:bg-slate-700 transition-all mt-4">Consult AI Copilot</button>
        </div>
    </div>

    <!-- Active Projects Table -->
    <div class="bg-white dark:bg-[#1a2634] rounded-2xl border border-neutral-border dark:border-slate-700 shadow-card overflow-hidden">
        <div class="p-6 border-b border-neutral-border dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase tracking-tight">Managed Execution Portfolios</h3>
            <button class="text-[10px] font-bold text-primary uppercase hover:underline">View All Projects</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Project / Identifier</th>
                        <th class="px-6 py-4">Timeline</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Burn Rate</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @foreach($projects as $project)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-primary border border-slate-200 dark:border-slate-700">
                                    <span class="material-icons text-lg">business</span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $project->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-widest">{{ $project->location }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($project->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($project->end_date)->format('M d') }}</span>
                                <span class="text-[10px] text-slate-500 font-medium">Q{{ ceil(\Carbon\Carbon::parse($project->start_date)->month / 3) }} Baseline</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-widest bg-green-50 text-green-600 border border-green-200 dark:bg-green-900/30 dark:border-green-800 dark:text-green-400">
                                {{ $project->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @php
                                    $weeks = max(1, \Carbon\Carbon::parse($project->start_date)->diffInWeeks(\Carbon\Carbon::parse($project->end_date)));
                                    $weeklyBurn = $project->budget / $weeks;
                                @endphp
                                <span class="text-xs font-bold text-slate-900 dark:text-white">${{ number_format($weeklyBurn, 0) }}/wk</span>
                                <span class="material-icons text-[14px] text-{{ $project->status === 'Completed' ? 'green' : 'amber' }}-500">trending_{{ $project->status === 'Completed' ? 'up' : 'flat' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('projects.show', $project) }}" class="text-slate-400 hover:text-primary transition-colors">
                                <span class="material-icons">chevron_right</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
