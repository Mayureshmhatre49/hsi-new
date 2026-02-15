<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - HSI Smart Execution System™</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "primary": "#137fec",
              "background-light": "#f6f7f8",
            }
          }
        }
      }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] min-h-screen flex flex-col items-center justify-center p-4">
    <!-- Background Decor -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary/20 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md relative z-10">
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden p-8 sm:p-12">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-xl mb-6 shadow-lg shadow-primary/20">
                    <span class="material-icons text-white text-3xl">architecture</span>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Access HSI Smart Execution System™</h1>
                <p class="text-slate-400 text-sm mt-2 font-medium uppercase tracking-widest text-[10px]">Portal Authentication Required</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="p-4 bg-red-500/10 border border-red-500/50 rounded-xl mb-6">
                        <ul class="text-xs text-red-500 font-bold uppercase tracking-widest list-none m-0 p-0 text-center">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Global Identifier (Email)</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xl">alternate_email</span>
                            <input type="email" name="email" value="{{ old('email', 'alex@hsi.com') }}" required class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-primary transition-all text-sm font-medium" placeholder="alex@hsi.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Access Protocol (Password)</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xl">lock_open</span>
                            <input type="password" name="password" value="password" required class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl py-3 pl-12 pr-4 text-white focus:outline-none focus:border-primary transition-all text-sm font-medium" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 bg-slate-800 border-slate-700 rounded text-primary focus:ring-offset-slate-900 focus:ring-primary h-4 w-4">
                        <span class="text-xs font-bold text-slate-400 group-hover:text-slate-300 uppercase tracking-tight">Remember Device</span>
                    </label>
                    <a href="#" class="text-xs font-bold text-primary hover:underline uppercase tracking-tight">Recovery Mode</a>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-widest text-xs">
                    Authorize & Launch Console
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    &copy; {{ date('Y') }} HSI Smart Execution System™ (Internal Access Only)
                </p>
            </div>
        </div>
    </div>
</body>
</html>
