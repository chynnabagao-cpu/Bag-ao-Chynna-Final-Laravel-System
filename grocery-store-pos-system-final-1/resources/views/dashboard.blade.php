@extends('layouts.app')

@section('title', 'Executive Overview')

@section('content')
<div class="space-y-8">
    <!-- Header & Range Selector -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight leading-none uppercase">Nicolle<span class="text-indigo-600"> Grocery Store</span></h1>
            <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1">POS & Inventory Management Dashboard</p>
        </div>

        <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200">
            @foreach(['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $key => $label)
                <a href="{{ route('dashboard', ['range' => $key]) }}"
                   class="px-5 py-2 rounded-xl text-sm font-bold transition-all {{ $range == $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl hover:shadow-indigo-500/5 transition-all">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 capitalize">{{ $range }} Revenue</p>
                <h3 class="text-2xl font-black text-slate-900">₱{{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl hover:shadow-emerald-500/5 transition-all">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 capitalize">{{ $range }} Orders</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalOrders }}</h3>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl hover:shadow-blue-500/5 transition-all">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Inventory Size</p>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalProducts }}</h3>
            </div>
        </div>

        <!-- Alert Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl hover:shadow-amber-500/5 transition-all">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 text-center">Stock Alerts</p>
                <h3 class="text-2xl font-black {{ $lowStockItems > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $lowStockItems }}</h3>
            </div>
        </div>

        <!-- Expiration Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-5 group hover:shadow-xl hover:shadow-red-500/5 transition-all">
            <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-2xl group-hover:bg-red-600 group-hover:text-white transition-colors">
                <i class="fas fa-calendar-times"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Expiry Alerts</p>
                <h3 class="text-2xl font-black {{ $expiringItems > 0 ? 'text-red-600' : 'text-slate-900' }}">{{ $expiringItems }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Best Sellers -->
        <div class="lg:col-span-1 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h3 class="font-black text-slate-800 uppercase tracking-tighter text-lg">Best Selling ({{ $range }})</h3>
            </div>
            <div class="p-4">
                @forelse($bestSellers as $item)
                <div class="flex items-center gap-4 p-4 hover:bg-slate-50 rounded-2xl transition-all group">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0">
                            @if($item->product && $item->product->image_path)
                                <img src="{{ str_starts_with($item->product->image_path, 'http') ? $item->product->image_path : asset('storage/' . $item->product->image_path) }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="absolute -top-2 -left-2 w-6 h-6 bg-indigo-600 text-white text-[10px] font-black rounded-lg flex items-center justify-center shadow-lg border-2 border-white">
                            {{ $loop->iteration }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-800 text-sm truncate leading-tight">
                            {{ $item->product ? $item->product->name : 'Unknown Product' }}
                        </p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-tight">{{ $item->total_qty }} Sold</p>
                            @if($item->product && $item->product->trashed())
                                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 text-[8px] font-black rounded uppercase italic">Archived</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-black text-slate-900">₱{{ number_format(($item->product ? $item->product->selling_price : 0) * $item->total_qty, 2) }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Revenue</p>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center opacity-20">
                    <i class="fas fa-layer-group text-4xl mb-2"></i>
                    <p class="text-xs font-black uppercase">No Data Available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-black text-slate-800 uppercase tracking-tighter text-lg">Recent Terminal Activity</h3>
                <a href="{{ route('reports.index') }}" class="text-indigo-600 text-xs font-black uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                            <th class="px-8 py-5">Receipt</th>
                            <th class="px-8 py-5">Cashier</th>
                            <th class="px-8 py-5">Method</th>
                            <th class="px-8 py-5 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($recentSales as $sale)
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-8 py-4 font-black text-slate-700 text-sm">{{ $sale->receipt_number }}</td>
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-slate-800 leading-none">{{ $sale->cashier->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold mt-1">{{ $sale->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-8 py-4">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full border border-indigo-100 uppercase">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="font-black text-slate-900">₱{{ number_format($sale->total_amount, 2) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
