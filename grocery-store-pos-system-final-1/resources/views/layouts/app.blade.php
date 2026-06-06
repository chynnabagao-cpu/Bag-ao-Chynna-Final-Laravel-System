
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nicolle Grocery Store - Terminal & Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-active { background: #4f46e5; color: white; shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); }

        /* Thermal Receipt Styling */
        .receipt {
            font-family: 'Courier New', Courier, monospace;
            width: 300px;
            background: white;
            padding: 20px;
            color: black;
            font-size: 14px;
        }

        .receipt-divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        @media print {
            body * { visibility: hidden; }
            #thermal-receipt, #thermal-receipt * { visibility: visible; }
            #thermal-receipt {
                position: absolute;
                left: 50%;
                top: 0;
                transform: translateX(-50%);
                width: 300px;
                box-shadow: none;
                border: none;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-[#0f172a] text-slate-400 flex flex-col fixed h-full">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 p-2 rounded-xl text-white">
                        <i class="fas fa-store text-xl"></i>
                    </div>
                    <h1 class="text-xs font-black text-white tracking-tighter uppercase">Nicolle<span class="text-indigo-500"> Grocery Store</span></h1>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                @if(auth()->user()->role === 'Admin')
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span class="font-bold">Dashboard</span>
                </a>
                @endif

                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('pos.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-cash-register w-5"></i>
                    <span class="font-bold">Terminal</span>
                </a>

                @if(auth()->user()->role === 'Admin')
                <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('inventory.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-boxes-stacked w-5"></i>
                    <span class="font-bold">Inventory</span>
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('categories.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tags w-5"></i>
                    <span class="font-bold">Categories</span>
                </a>
                <a href="{{ route('promotions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('promotions.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-wand-magic-sparkles w-5"></i>
                    <span class="font-bold">Discounts</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('reports.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-file-invoice-dollar w-5"></i>
                    <span class="font-bold">Reports</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('users.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-bold">Staff</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('settings.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-bold">Settings</span>
                </a>
                @endif
            </nav>

            <div class="p-6 mt-auto border-t border-white/5">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-black">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] uppercase font-black text-slate-500 tracking-widest mt-1">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all font-bold">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-72">
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 px-8 py-4 flex items-center justify-between">
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">
                    @yield('title', 'Terminal')
                </h2>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p id="live-date" class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Loading...</p>
                            <p id="live-clock" class="text-xl font-black text-indigo-600 leading-none">00:00:00</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shadow-sm">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <!-- Global Notifications -->
                <div class="fixed top-24 right-8 z-[60] w-96 space-y-4 no-print" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    @if(session('success'))
                        <div class="bg-emerald-600 text-white p-5 rounded-3xl shadow-2xl flex items-start gap-4 animate-slide-in relative overflow-hidden group">
                            <div class="absolute inset-0 bg-white/10 w-0 group-hover:w-full transition-all duration-[5000ms] ease-linear"></div>
                            <i class="fas fa-check-circle text-2xl mt-0.5"></i>
                            <div class="relative z-10">
                                <p class="font-black text-sm uppercase tracking-tight leading-none mb-1">Success</p>
                                <p class="text-xs font-bold opacity-90 leading-relaxed">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-600 text-white p-5 rounded-3xl shadow-2xl flex items-start gap-4 animate-slide-in relative overflow-hidden group">
                            <i class="fas fa-exclamation-triangle text-2xl mt-0.5"></i>
                            <div class="relative z-10">
                                <p class="font-black text-sm uppercase tracking-tight leading-none mb-1">Operational Error</p>
                                <p class="text-xs font-bold opacity-90 leading-relaxed">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-amber-500 text-white p-5 rounded-3xl shadow-2xl flex items-start gap-4 animate-slide-in relative overflow-hidden group">
                            <i class="fas fa-info-circle text-2xl mt-0.5"></i>
                            <div class="relative z-10">
                                <p class="font-black text-sm uppercase tracking-tight leading-none mb-1">Validation Required</p>
                                <ul class="text-[10px] font-bold opacity-90 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const dateOptions = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };

            const clockEl = document.getElementById('live-clock');
            const dateEl = document.getElementById('live-date');

            if(clockEl) clockEl.innerText = now.toLocaleTimeString('en-US', timeOptions);
            if(dateEl) dateEl.innerText = now.toLocaleDateString('en-US', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
