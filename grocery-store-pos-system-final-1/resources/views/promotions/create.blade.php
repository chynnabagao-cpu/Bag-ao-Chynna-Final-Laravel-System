@extends('layouts.app')

@section('title', 'New Promotion Campaign')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-5 mb-10">
            <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-600 text-white flex items-center justify-center text-2xl shadow-xl shadow-indigo-100">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase">New Campaign</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Promotion Strategy</p>
            </div>
        </div>

        <form action="{{ route('promotions.store') }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-2">
                    <label class="text-[11px] font-black uppercase text-indigo-500 tracking-[0.2em] ml-1 mb-2 block">Campaign Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g. Summer Sale, Weekend Blast"
                           class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-black text-slate-800 lowercase">
                </div>

                <div>
                    <label class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Apply to Product</label>
                    <select name="product_id" required
                            class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                        <option value="">Select Target Item</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} (₱{{ number_format($product->selling_price, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Discount Percentage</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="discount_percentage" value="{{ old('discount_percentage', 10.00) }}" required
                               class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-black text-slate-800">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 font-black text-slate-400">%</span>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                           class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-black text-slate-800">
                </div>

                <div>
                    <label class="text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+1 month'))) }}" required
                           class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-black text-slate-800">
                </div>
            </div>

            <div class="flex gap-4 pt-8">
                <a href="{{ route('promotions.index') }}" class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-slate-200 transition-colors">
                    CANCEL
                </a>
                <button type="submit" class="flex-2 px-12 py-5 bg-black text-white rounded-2xl font-black shadow-lg hover:bg-slate-800 transition-all border-b-4 border-indigo-900 active:border-b-0 active:translate-y-1">
                    LAUNCH CAMPAIGN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
