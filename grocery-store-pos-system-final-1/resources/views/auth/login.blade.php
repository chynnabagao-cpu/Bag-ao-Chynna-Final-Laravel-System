
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GroceryPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Abstract Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-500 rounded-full blur-[120px]"></div>
    </div>

<div class="max-w-md w-full p-6 relative z-10">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center bg-gradient-to-br from-indigo-500 to-indigo-700 text-white p-5 rounded-[2rem] shadow-2xl mb-6 ring-8 ring-indigo-500/10">
            <i class="fas fa-store text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-white tracking-tighter leading-none">NICOLLE<br><span class="text-indigo-400">GROCERY STORE</span></h1>
        <p class="text-slate-400 mt-2 font-medium tracking-wide uppercase text-[10px]">POS Management and Inventory System</p>
    </div>

    <div class="bg-white/10 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-white/10">
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-2xl text-sm font-medium">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-2">
                <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest ml-1">Terminal Operator</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="text" name="username" required
                           class="w-full pl-12 pr-4 py-4 bg-white/5 border-2 border-white/5 rounded-2xl focus:bg-white/10 focus:border-indigo-500/50 text-white outline-none transition-all placeholder:text-slate-600 font-bold"
                           placeholder="Username" value="{{ old('username') }}">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest ml-1">Access Pin</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 group-focus-within:text-indigo-400 transition-colors">
                        <i class="fas fa-key"></i>
                    </span>
                    <input type="password" name="password" required
                           class="w-full pl-12 pr-4 py-4 bg-white/5 border-2 border-white/5 rounded-2xl focus:bg-white/10 focus:border-indigo-500/50 text-white outline-none transition-all placeholder:text-slate-600 font-bold"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-lg hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/40 active:scale-[0.98] border-b-4 border-indigo-800 hover:border-b-2 hover:translate-y-[2px]">
                UNLOCK TERMINAL
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 p-4 rounded-2xl border border-white/5 text-center group hover:bg-white/10 transition-colors">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-tighter mb-1">Admin Access</p>
                    <p class="text-xs text-slate-400 font-bold">admin / secret</p>
                </div>
                <div class="bg-white/5 p-4 rounded-2xl border border-white/5 text-center group hover:bg-white/10 transition-colors">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-tighter mb-1">Cashier Access</p>
                    <p class="text-xs text-slate-400 font-bold">cashier1 / secret</p>
                </div>
            </div>
        </div>
    </div>

    <p class="text-center mt-8 text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">
        Secured by FreshMart Enterprise Cloud
    </p>
</div>

</body>
</html>
