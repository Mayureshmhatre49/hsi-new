@extends('layouts.app')

@section('title', 'BOQ Intelligence - HSI Smart Execution System™')
@section('page_title', 'BOQ & Interior Planning')

@section('content')
<div 
    x-data="{
        simulationMode: true,
        wasteFactor: 5.0,
        targetMargin: 18.5,
        contingency: 5000,
        originalTotal: 145200,
        get simulatedTotal() {
            return this.originalTotal * (1 + (this.wasteFactor - 5) / 100) * (1 + (this.targetMargin - 15) / 100) + (this.contingency - 5000);
        },
        get variance() {
            return ((this.simulatedTotal - this.originalTotal) / this.originalTotal) * 100;
        },
        formatCurrency(val) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
        }
    }"
    class="flex h-[calc(100vh-140px)] -m-6 overflow-hidden bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm"
>
    <!-- Left Panel: BOQ Grid -->
    <section class="flex-1 flex flex-col min-w-0 relative">
        <!-- Grid Toolbar -->
        <div class="h-14 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <span class="material-icons absolute left-2.5 top-2.5 text-slate-400 text-sm">search</span>
                    <input
                        class="pl-9 pr-4 py-1.5 w-64 text-sm bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-md focus:ring-2 focus:ring-primary focus:border-primary transition-shadow"
                        placeholder="Search line items..."
                        type="text"
                    />
                </div>
                <button class="flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-primary transition-colors uppercase tracking-wider">
                    <span class="material-icons text-lg">filter_list</span>
                    Filter
                </button>
            </div>
            <div class="flex items-center gap-4 text-xs font-medium text-slate-500">
                <span>142 items</span>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <span class="flex items-center gap-1.5">
                    Total: <strong class="text-slate-900 dark:text-white" x-text="formatCurrency(originalTotal)"></strong>
                </span>
            </div>
        </div>

        <!-- Data Grid -->
        <div class="flex-1 overflow-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-20 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="p-4 border-b border-r border-slate-200 dark:border-slate-700 w-12 text-center">
                            <input class="rounded border-slate-300 text-primary focus:ring-primary h-3.5 w-3.5" type="checkbox" />
                        </th>
                        <th class="p-3 border-b border-r border-slate-200 dark:border-slate-700 w-24">Code</th>
                        <th class="p-3 border-b border-r border-slate-200 dark:border-slate-700 min-w-[280px]">Description</th>
                        <th class="p-3 border-b border-r border-slate-200 dark:border-slate-700 w-20 text-center">Unit</th>
                        <th class="p-3 border-b border-r border-slate-200 dark:border-slate-700 w-24 text-right">Qty</th>
                        <th class="p-3 border-b border-r border-slate-200 dark:border-slate-700 w-32 text-right">Rate</th>
                        <th class="p-3 border-b border-slate-200 dark:border-slate-700 w-36 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 dark:text-slate-200 divide-y divide-slate-100 dark:divide-slate-800">
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <td class="px-4 py-2 font-bold text-slate-400 text-[10px] uppercase tracking-wider" colSpan="7">
                            01. General Requirements
                        </td>
                    </tr>
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="p-4 border-r border-slate-100 dark:border-slate-800 text-center">
                            <input class="rounded border-slate-300 text-primary focus:ring-primary h-3.5 w-3.5" type="checkbox" />
                        </td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 font-mono text-[11px] text-slate-400">GEN-001</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 font-medium">Site Mobilization & Setup</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 text-center text-xs text-slate-400">LS</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono">1.00</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono">$5,000.00</td>
                        <td class="p-3 text-right font-mono font-bold text-primary">$5,000.00</td>
                    </tr>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <td class="px-4 py-2 font-bold text-slate-400 text-[10px] uppercase tracking-wider" colSpan="7">
                            03. Finishes - Flooring
                        </td>
                    </tr>
                    @php
                        $items = [
                            ['code' => 'FLR-M01', 'desc' => 'Italian Carrara Marble (60x60cm)', 'unit' => 'm²', 'qty' => '120.00', 'rate' => '85.50', 'total' => '10,260.00'],
                            ['code' => 'FLR-W03', 'desc' => 'Engineered Oak Wood Flooring', 'unit' => 'm²', 'qty' => '215.50', 'rate' => '62.00', 'total' => '13,361.00'],
                        ];
                    @endphp
                    @foreach($items as $item)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="p-4 border-r border-slate-100 dark:border-slate-800 text-center">
                            <input class="rounded border-slate-300 text-primary focus:ring-primary h-3.5 w-3.5" type="checkbox" />
                        </td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 font-mono text-[11px] text-slate-400">{{ $item['code'] }}</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 font-medium">{{ $item['desc'] }}</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 text-center text-xs text-slate-400">{{ $item['unit'] }}</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono">{{ $item['qty'] }}</td>
                        <td class="p-3 border-r border-slate-100 dark:border-slate-800 text-right font-mono">${{ $item['rate'] }}</td>
                        <td class="p-3 text-right font-mono font-bold text-primary">${{ $item['total'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Grid Footer -->
        <div class="h-16 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 z-20">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                Aggregated Total
            </div>
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <div class="text-[10px] text-slate-400 uppercase font-bold">Planned Cost</div>
                    <div class="font-mono text-slate-900 dark:text-white font-bold text-lg" x-text="formatCurrency(originalTotal)"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Right Panel: Margin Simulator -->
    <aside class="w-80 bg-slate-50 dark:bg-[#15202B] border-l border-slate-200 dark:border-slate-800 flex flex-col shrink-0">
        <div class="h-14 px-5 border-b border-slate-200 dark:border-slate-800 flex items-center bg-white dark:bg-slate-900">
            <h2 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 text-sm uppercase tracking-wide">
                <span class="material-icons text-primary text-lg">insights</span>
                Margin Intelligence
            </h2>
        </div>
        <div class="flex-1 overflow-y-auto p-5 space-y-6">
            <!-- Mode Toggle -->
            <div class="p-4 bg-primary/5 dark:bg-primary/10 rounded-xl border border-primary/20">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-primary uppercase text-[11px]">Simulation Mode</span>
                    <button @click="simulationMode = !simulationMode" class="w-8 h-4 bg-slate-200 dark:bg-slate-700 rounded-full relative transition-colors" :class="simulationMode ? 'bg-primary' : ''">
                        <div class="absolute inset-y-0.5 left-0.5 w-3 h-3 bg-white rounded-full transition-transform" :class="simulationMode ? 'translate-x-4' : ''"></div>
                    </button>
                </div>
            </div>

            <!-- Parameters -->
            <div class="space-y-5" :class="simulationMode || 'opacity-40 pointer-events-none'">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Waste Factor</label>
                    <div class="flex items-center gap-3">
                        <input type="range" min="0" max="15" step="0.5" x-model="wasteFactor" class="flex-1 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-primary">
                        <span class="text-xs font-mono font-bold w-10 text-right" x-text="wasteFactor + '%'"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Target Margin</label>
                    <div class="flex items-center gap-3">
                        <input type="range" min="5" max="35" step="0.5" x-model="targetMargin" class="flex-1 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-primary">
                        <span class="text-xs font-mono font-bold w-10 text-right" x-text="targetMargin + '%'"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-widest">Contingency Fund</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-slate-400 text-xs">$</span>
                        <input type="number" x-model="contingency" class="w-full pl-7 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-mono focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="h-px bg-slate-200 dark:bg-slate-800"></div>

            <!-- Impact -->
            <div class="space-y-4">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Simulated Impact</h3>
                <div class="p-4 bg-slate-900 border border-slate-800 rounded-xl space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400">Baseline Price</span>
                        <span class="text-slate-500 font-mono line-through" x-text="formatCurrency(originalTotal)"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-100 uppercase tracking-wide">Target Price</span>
                        <span class="text-xl font-mono font-bold text-primary" x-text="formatCurrency(simulatedTotal)"></span>
                    </div>
                    <div class="pt-3 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Variance</span>
                        <div class="flex items-center gap-2" :class="variance >= 0 ? 'text-green-400' : 'text-red-400'">
                            <span class="material-icons text-xs" x-text="variance >= 0 ? 'north_east' : 'south_east'"></span>
                            <span class="font-mono text-xs font-bold text-lg" x-text="(variance >= 0 ? '+' : '') + variance.toFixed(2) + '%'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-5 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
            <button class="w-full py-3 bg-primary hover:bg-blue-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest shadow-lg shadow-primary/30 transition-all active:scale-95">
                Commit Projection
            </button>
        </div>
    </aside>
</div>
@endsection
