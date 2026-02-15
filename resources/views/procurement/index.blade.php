@extends('layouts.app')

@section('title', 'Procurement & Vendor Intelligence - HSI Smart Execution System™')
@section('page_title', 'Procurement & Supply Chain')

@section('content')
<div class="space-y-8" x-data="{ view: 'pipeline' }">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Procurement Dashboard</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Global supply chain tracking and vendor performance intelligence.</p>
        </div>
        <div class="flex bg-white dark:bg-slate-800 p-1 rounded-lg border border-slate-200 dark:border-slate-700">
            <button @click="view = 'pipeline'" :class="view === 'pipeline' ? 'bg-primary text-white' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all uppercase tracking-widest">
                Pipeline
            </button>
            <button @click="view = 'intelligence'" :class="view === 'intelligence' ? 'bg-primary text-white' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-xs font-bold rounded-md transition-all uppercase tracking-widest">
                Intelligence
            </button>
        </div>
    </div>

    <!-- Procurement Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Committed', 'val' => '$8.4M', 'icon' => 'payments', 'color' => 'blue'],
                ['label' => 'Active Orders', 'val' => '42', 'icon' => 'shopping_bag', 'color' => 'indigo'],
                ['label' => 'In Transit', 'val' => '18', 'icon' => 'local_shipping', 'color' => 'amber'],
                ['label' => 'Delayed Items', 'val' => '3', 'icon' => 'warning', 'color' => 'red'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white dark:bg-[#1a2634] p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-card">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/20 rounded-lg text-{{ $stat['color'] }}-500">
                    <span class="material-icons text-lg">{{ $stat['icon'] }}</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</p>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stat['val'] }}</h3>
        </div>
        @endforeach
    </div>

    <!-- Pipeline View -->
    <div x-show="view === 'pipeline'" x-transition class="bg-white dark:bg-[#1a2634] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest">Global Order Pipeline</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-6 py-4">Reference</th>
                        <th class="px-6 py-4">Vendor</th>
                        <th class="px-6 py-4">Logistics Phase</th>
                        <th class="px-6 py-4">ETA Variance</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @php
                        $orders = [
                            ['ref' => 'PO-2024-012', 'vendor' => 'Apex Materials', 'phase' => 'Customs Clearance', 'var' => '+2 Days', 'color' => 'amber'],
                            ['ref' => 'PO-2024-045', 'vendor' => 'Lumina Lighting', 'phase' => 'Last Mile Delivery', 'var' => 'On Time', 'color' => 'green'],
                            ['ref' => 'PO-2024-088', 'vendor' => 'Velvet Touch FF&E', 'phase' => 'Ex-Factory Packaging', 'var' => '-5 Days', 'color' => 'red'],
                        ];
                    @endphp

                    @foreach($orders as $order)
                    <tr class="hover:bg-primary/5 transition-colors">
                        <td class="px-6 py-5 font-mono text-[11px] font-bold text-primary">{{ $order['ref'] }}</td>
                        <td class="px-6 py-5 font-bold text-slate-900 dark:text-white">{{ $order['vendor'] }}</td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-medium">{{ $order['phase'] }}</span>
                                <div class="w-24 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-{{ $order['color'] }}-50 dark:bg-{{ $order['color'] }}-900/20 text-{{ $order['color'] }}-600">{{ $order['var'] }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <button class="material-icons text-slate-400 text-lg hover:text-primary transition-colors">track_changes</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Intelligence View -->
    <div x-show="view === 'intelligence'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-8">
        <div class="bg-white dark:bg-[#1a2634] p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Vendor Performance Ranking</h3>
            <div class="space-y-6">
                @php
                    $vendors = [
                        ['name' => 'Apex Materials', 'score' => 9.8, 'img' => 'https://ui-avatars.com/api/?name=Apex&background=137fec&color=fff'],
                        ['name' => 'Lumina Systems', 'score' => 8.4, 'img' => 'https://ui-avatars.com/api/?name=Lumina&background=137fec&color=fff'],
                        ['name' => 'Velvet Touch', 'score' => 6.2, 'img' => 'https://ui-avatars.com/api/?name=Velvet&background=137fec&color=fff'],
                    ];
                @endphp

                @foreach($vendors as $v)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $v['img'] }}" class="w-8 h-8 rounded-lg shadow-sm" />
                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $v['name'] }}</span>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-bold text-primary">{{ $v['score'] }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">/10</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gradient-to-br from-primary/5 to-white dark:to-[#1a2634] dark:from-primary/10 p-6 rounded-2xl border border-primary/20 shadow-soft relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-3 opacity-10">
                <span class="material-icons text-6xl text-primary">auto_awesome</span>
            </div>
            <div class="flex items-center gap-2 mb-4">
                <span class="material-icons text-primary text-sm animate-pulse">auto_awesome</span>
                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Procurement Insights</span>
            </div>
            <div class="p-4 bg-white/50 dark:bg-black/20 rounded-xl backdrop-blur-md border border-white/20">
                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    AI detects a <strong class="text-primary">12% price optimization window</strong> for marble fixtures. Velvet Touch has excess inventory in Sector 2. Suggest negotiating bulk procurement for the Tower 42 project.
                </p>
            </div>
            <button class="mt-4 text-[10px] font-bold text-primary uppercase tracking-widest flex items-center gap-1 hover:underline">
                Generate Negotiation Deck <span class="material-icons text-xs">arrow_forward</span>
            </button>
        </div>
    </div>
</div>
@endsection
