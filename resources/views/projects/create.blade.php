@extends('layouts.app')

@section('title', 'Create Project - HSI Smart Execution System™')
@section('page_title', 'Configure New Plan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-[#1a2634] rounded-2xl border border-slate-200 dark:border-slate-700 shadow-card overflow-hidden">
        <div class="p-8 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-icons text-primary">add_business</span>
                Initialize Execution Module
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Define the baseline parameters for the new interior execution site.</p>
        </div>

        <form action="{{ route('projects.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            @if ($errors->any())
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <ul class="text-xs text-red-600 dark:text-red-400 font-bold uppercase tracking-widest list-none m-0 p-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Identity -->
                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold text-primary uppercase tracking-widest border-b border-primary/10 pb-2">Project Identity</h3>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Internal Designation</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-sm transition-all" placeholder="e.g. Tower 42 Interior Ph. 2">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Client Entity</label>
                        <input type="text" name="client" value="{{ old('client') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-sm transition-all" placeholder="e.g. Meta Global Operations">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Sovereign Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-sm transition-all" placeholder="e.g. London, UK">
                    </div>
                </div>

                <!-- Financial & Schedule -->
                <div class="space-y-4">
                    <h3 class="text-[10px] font-bold text-primary uppercase tracking-widest border-b border-primary/10 pb-2">Fiscal & Temporal Metrics</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Commencement</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-xs transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Target Completion</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-xs transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Authorized Budget (CapEx)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                            <input type="number" name="budget" value="{{ old('budget') }}" step="0.01" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 pl-8 pr-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-sm transition-all" placeholder="5000000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 px-1">Projected Margin (%)</label>
                        <div class="relative">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                            <input type="number" name="margin_projection" value="{{ old('margin_projection') }}" step="0.1" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-4 text-slate-900 dark:text-white focus:ring-1 focus:ring-primary focus:outline-none text-sm transition-all" placeholder="15.5">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('projects.index') }}" class="px-6 py-2.5 text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-slate-700 dark:hover:text-slate-300 transition-all">Cancel</a>
                <button type="submit" class="px-8 py-2.5 bg-primary text-white text-[10px] font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                    Generate Protocol
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
