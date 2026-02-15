<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'HSI Smart Execution System™')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
    @yield('styles')
</head>
<body 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true', 
        copilotOpen: false,
        chatInput: '',
        messages: [
            { role: 'ai', content: 'How can I assist your project operations today, Alex?' }
        ],
        isTyping: false,
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        },
        async sendMessage() {
            if (!this.chatInput.trim()) return;
            
            const userMsg = this.chatInput;
            this.messages.push({ role: 'user', content: userMsg });
            this.chatInput = '';
            this.isTyping = true;

            try {
                const response = await fetch('{{ route('copilot.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: userMsg, project_id: {{ $project->id ?? 'null' }} })
                });
                
                const data = await response.json();
                this.messages.push({ role: 'ai', content: data.reply, insights: data.insights });
            } catch (error) {
                this.messages.push({ role: 'ai', content: 'Protocol error: Unable to reach AI core.' });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => {
                    const container = this.$refs.chatContainer;
                    container.scrollTop = container.scrollHeight;
                });
            }
        }
    }" 
    :class="{ 'dark': darkMode }"
    class="bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-100 font-display antialiased h-screen flex overflow-hidden"
    x-cloak
>
    <!-- Sidebar -->
    <aside class="w-64 bg-neutral-surface dark:bg-[#1a2634] border-r border-neutral-border dark:border-slate-700 hidden md:flex flex-col h-full shrink-0 transition-all duration-300">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-neutral-border dark:border-slate-700">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white font-bold text-lg">H</div>
                <span class="font-bold text-sm tracking-tight text-slate-900 dark:text-white uppercase">HSI Smart Exec™</span>
            </div>
        </div>
        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <a class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} rounded-lg font-medium" href="{{ route('dashboard') }}">
                <span class="material-icons text-[20px]">dashboard_customize</span>
                <span class="text-sm">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('projects.*') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} rounded-lg transition-colors group" href="{{ route('projects.index') }}">
                <span class="material-icons text-[20px] group-hover:text-primary transition-colors">folder_shared</span>
                <span class="text-sm font-medium">Projects</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('planning.boq') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} rounded-lg transition-colors group" href="{{ route('planning.boq') }}">
                <span class="material-icons text-[20px] group-hover:text-primary transition-colors">precision_manufacturing</span>
                <span class="text-sm font-medium">BOQ Intelligence</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('procurement.index') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} rounded-lg transition-colors group" href="{{ route('procurement.index') }}">
                <span class="material-icons text-[20px] group-hover:text-primary transition-colors">inventory_2</span>
                <span class="text-sm font-medium">Procurement</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('finance.*') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} rounded-lg transition-colors group" href="{{ route('finance.index') }}">
                <span class="material-icons text-[20px] group-hover:text-primary transition-colors">payments</span>
                <span class="text-sm font-medium">Finance</span>
            </a>
            <!-- Copilot Trigger -->
            <button @click="copilotOpen = true" class="w-full flex items-center gap-3 px-3 py-2.5 mt-4 bg-gradient-to-r from-primary to-blue-600 text-white rounded-lg shadow-lg shadow-primary/20 transition-transform active:scale-95 group">
                <span class="material-icons text-[20px] animate-pulse">smart_toy</span>
                <span class="text-sm font-bold">Ask AI Copilot</span>
            </button>
        </nav>
        <!-- User Profile & Session -->
        <div class="p-4 border-t border-neutral-border dark:border-slate-700 space-y-2">
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer group" @click="toggleDarkMode()">
                <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700 group-hover:border-primary/30">
                    <span class="material-icons text-lg text-slate-500" x-show="!darkMode">dark_mode</span>
                    <span class="material-icons text-lg text-amber-400" x-show="darkMode">light_mode</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Theme</p>
                    <p class="text-[10px] font-bold text-slate-500 truncate" x-text="darkMode ? 'Dark Protocol' : 'Light Protocol'"></p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors cursor-pointer group" onclick="document.getElementById('logout-form').submit();">
                <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700 group-hover:border-red-500/30">
                    <span class="material-icons text-lg text-slate-400 group-hover:text-red-500">logout</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Session</p>
                    <p class="text-[10px] font-bold text-slate-500 truncate group-hover:text-red-500">Terminate Access</p>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Header -->
        <header class="h-16 bg-neutral-surface/80 dark:bg-[#1a2634]/90 backdrop-blur-md border-b border-neutral-border dark:border-slate-700 flex items-center justify-between px-6 z-10">
            <div class="flex items-center gap-4">
                <button class="md:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
                    <span class="material-icons">menu</span>
                </button>
                <h1 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                    @yield('page_title', 'Executive Portfolio Overview')
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1.5 w-64 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                    <span class="material-icons text-slate-400 text-[18px]">search</span>
                    <input class="bg-transparent border-none text-sm ml-2 w-full focus:ring-0 text-slate-700 dark:text-slate-200 p-0" placeholder="Search insights..." type="text"/>
                </div>
                <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                    <span class="material-icons">notifications_active</span>
                </button>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-6 scroll-smooth bg-slate-50 dark:bg-[#101922]">
            <!-- Global Flash Messages -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="material-icons text-green-500">check_circle</span>
                <p class="text-sm font-bold text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-xl shadow-sm animate-fade-in">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-icons text-red-500">error</span>
                    <p class="text-sm font-bold text-red-700 dark:text-red-400">Submission Error</p>
                </div>
                <ul class="list-disc list-inside text-xs text-red-600 dark:text-red-300 ml-6 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </div>

        <!-- AI Copilot Sidebar (Overlay) -->
        <div 
            x-show="copilotOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 w-96 z-50 flex flex-col bg-white dark:bg-[#101922] border-l border-slate-200 dark:border-slate-800 shadow-2xl"
            style="display: none;"
        >
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white">
                        <span class="material-icons text-sm">smart_toy</span>
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white uppercase text-sm">HSI Copilot</span>
                </div>
                <button @click="copilotOpen = false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                    <span class="material-icons text-slate-400">close</span>
                </button>
            </div>
            <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-6 space-y-6 flex flex-col">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'self-end bg-primary text-white' : 'self-start bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300'" class="p-4 rounded-xl max-w-[90%] shadow-sm">
                        <p class="text-sm font-medium" x-text="msg.content"></p>
                        
                        <!-- AI Insights -->
                        <template x-if="msg.insights && msg.insights.length > 0">
                            <div class="mt-4 space-y-3">
                                <template x-for="insight in msg.insights">
                                    <div class="bg-white/10 border border-white/20 p-2.5 rounded-lg">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="material-icons text-[14px]" :class="insight.severity === 'high' ? 'text-red-400' : 'text-amber-400'">warning</span>
                                            <span class="text-[10px] font-bold uppercase tracking-widest" x-text="insight.type"></span>
                                        </div>
                                        <p class="text-[11px] font-bold" x-text="insight.title"></p>
                                        <p class="text-[10px] opacity-80 mt-1" x-text="insight.content"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                <div x-show="isTyping" class="self-start bg-slate-100 dark:bg-slate-800 p-4 rounded-xl rounded-tl-none animate-pulse">
                    <div class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                <div class="flex gap-2">
                    <input 
                        x-model="chatInput" 
                        @keydown.enter="sendMessage()"
                        class="flex-1 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm px-4 py-2 focus:ring-1 focus:ring-primary text-slate-900 dark:text-white" 
                        placeholder="Inquire project telemetry..."
                    />
                    <button @click="sendMessage()" class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center shadow-lg shadow-primary/30 active:scale-90 transition-transform">
                        <span class="material-icons text-lg">send</span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    @yield('scripts')
</body>
</html>
