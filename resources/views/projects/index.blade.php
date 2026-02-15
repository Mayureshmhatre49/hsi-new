@extends('layouts.app')

@section('title', 'Portfolio Overview - HSI Smart Execution System™')
@section('page_title', 'Portfolio Overview')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'benchmarks' }">
    <!-- Header & Filters -->
    <header class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Portfolio Overview</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Real-time risk assessment across {{ $projects->count() }} active interior execution sites.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-primary hover:bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                <span class="material-icons text-sm">add</span>
                Initialize New Plan
            </a>
            <div class="flex bg-white dark:bg-slate-800 p-1 rounded-lg border border-slate-200 dark:border-slate-700">
                <button @click="activeTab = 'benchmarks'" :class="activeTab === 'benchmarks' ? 'bg-primary text-white' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all uppercase tracking-widest">
                    Benchmarks
                </button>
                <button @click="activeTab = 'heatmap'" :class="activeTab === 'heatmap' ? 'bg-primary text-white' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all uppercase tracking-widest">
                    Risk Heatmap
                </button>
            </div>
        </div>
    </header>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($kpis as $kpi)
        <div class="bg-white dark:bg-[#1a2634] p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-card">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">{{ $kpi['label'] }}</p>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ $kpi['val'] }}</span>
                <span class="text-[10px] font-bold text-{{ $kpi['color'] === 'green' ? 'green' : ($kpi['color'] === 'red' ? 'red' : 'primary') }}-600 bg-{{ $kpi['color'] }}-50 dark:bg-{{ $kpi['color'] }}-900/20 px-2 py-0.5 rounded">{{ $kpi['change'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Benchmarks Tab -->
    <div x-show="activeTab === 'benchmarks'" x-transition class="bg-white dark:bg-[#1a2634] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest">Global Benchmarks</h3>
            <div class="flex gap-2">
                <div class="relative">
                    <span class="material-icons absolute left-2.5 top-2 text-slate-400 text-sm">search</span>
                    <input class="pl-8 pr-3 py-1 text-xs bg-slate-50 dark:bg-slate-800 border-none rounded-lg w-48 focus:ring-1 focus:ring-primary" placeholder="Filter projects..."/>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-6 py-4">Project Entity</th>
                        <th class="px-6 py-4">Region</th>
                        <th class="px-6 py-4">Budget Utilization</th>
                        <th class="px-6 py-4">Schedule Health</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @foreach($projects as $project)
                    @php
                        $actual = $project->expenses->sum('amount');
                        $utilization = $project->budget > 0 ? ($actual / $project->budget) * 100 : 0;
                        $isOverrun = $utilization > 100;
                        // Simple logic for schedule health based on end date vs now if status is not 'Completed'
                        $daysLeft = \Carbon\Carbon::now()->diffInDays($project->end_date, false);
                        $scheduleStatus = $daysLeft < 0 && $project->status !== 'Completed' ? 'Legacy/Delay' : 'On Track';
                        $scheduleColor = $daysLeft < 0 && $project->status !== 'Completed' ? 'red' : 'green';
                    @endphp
                    <tr class="hover:bg-primary/5 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-icons text-sm">business</span>
                                </div>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $project->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-medium text-slate-500 uppercase text-[11px] tracking-wide">{{ $project->location }}</td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-bold text-slate-900 dark:text-white">{{ number_format($utilization, 1) }}%</span>
                                <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full {{ $isOverrun ? 'bg-red-500' : 'bg-primary' }} rounded-full" style="width: {{ min(100, $utilization) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-1.5 font-bold text-[10px] uppercase">
                                <span class="w-2 h-2 rounded-full bg-{{ $scheduleColor }}-500"></span>
                                {{ $scheduleStatus }}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('projects.show', $project) }}" class="material-icons text-slate-400 text-lg hover:text-primary transition-colors">launch</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Heatmap Tab -->
    <div x-show="activeTab === 'heatmap'" x-transition class="bg-white dark:bg-[#1a2634] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card p-12 flex flex-col items-center justify-center text-center space-y-4">
        <div class="w-24 h-24 rounded-full bg-primary/5 flex items-center justify-center">
            <span class="material-icons text-primary text-5xl animate-pulse">map</span>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Spatial Risk Visualization</h3>
        <p class="text-slate-500 text-sm max-w-md">The Interactive Risk Heatmap requires regional telemetry data. Use the AI Copilot to aggregate spatial insights from the BOQ workspace.</p>
        <button class="px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold uppercase tracking-widest shadow-lg shadow-primary/20">
            Initialize Mapper
        </button>
    </div>
</div>
@endsection
