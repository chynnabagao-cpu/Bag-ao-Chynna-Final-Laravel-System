@extends('layouts.app')

@section('title', 'Inventory Management')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Stock Control</h1>
            <p class="text-slate-500 font-medium">Manage your products and monitor stock levels</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('inventory.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> ADD PRODUCT
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Items</p>
                <p class="text-xl font-black text-slate-900">{{ $products->total() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Low Stock Alerts</p>
                <p class="text-xl font-black text-amber-600">{{ $lowStockCount }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fas fa-tags text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Categories</p>
                <p class="text-xl font-black text-slate-900">{{ \App\Models\Category::count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-8 py-5">Product Details</th>
                        <th class="px-8 py-5">Category</th>
                        <th class="px-8 py-5">Pricing (Cost/Sell)</th>
                        <th class="px-8 py-5">Stock Level</th>
                        <th class="px-8 py-5">Expiration Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($products as $product)
                    <tr class="hover:bg-slate-50 transition-all group">
                        <td class="px-8 py-5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                                @if($product->image_path)
                                    <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-black text-slate-800 tracking-tight">{{ $product->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $product->barcode }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-full uppercase">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-900">₱{{ number_format($product->selling_price, 2) }}</span>
                                <span class="text-[10px] font-bold text-slate-400">COST: ₱{{ number_format($product->cost_price, 2) }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-black {{ $product->stock_quantity <= $product->min_stock_threshold ? 'text-amber-600' : 'text-slate-900' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                                <div class="w-24 bg-slate-100 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full bg-indigo-500" style="width: {{ min(($product->stock_quantity / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @if($product->expiration_date)
                                @php
                                    $expiry = \Carbon\Carbon::parse($product->expiration_date);
                                    $daysRemaining = now()->diffInDays($expiry, false);
                                    
                                    // NEW LOGIC: If 10 days or less, it's considered EXPIRED/CRITICAL
                                    $isExpired = $daysRemaining <= 10;
                                    // SOON: Only if more than 10 days but less than 30
                                    $isNear = $daysRemaining > 10 && $daysRemaining <= 30;
                                @endphp
                                
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-black text-slate-800">{{ $expiry->format('M d, Y') }}</span>
                                    @if($isExpired)
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full animate-pulse"></span>
                                            <span class="text-[10px] font-black text-red-600 uppercase">EXPIRED / REMOVE</span>
                                        </div>
                                    @elseif($isNear)
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            <span class="text-[10px] font-black text-amber-600 uppercase">EXPIRING SOON</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            <span class="text-[10px] font-black text-emerald-600 uppercase">SAFE STOCK</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-300 text-[10px] font-black uppercase tracking-widest italic opacity-50">No Expiry Set</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('inventory.edit', $product->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-8 bg-slate-50/50 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
