@extends('layouts.app')

@section('title', 'Promotions & Discounts')

@section('content')
<div class="max-w-6xl mx-auto space-y-10 py-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Promotions & Discounts</h1>
            <p class="text-slate-500 font-medium">Manage your active campaigns and targeted price reductions.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('promotions.create') }}" class="bg-black text-white px-6 py-3 rounded-[0.8rem] font-bold shadow-lg hover:bg-slate-800 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i> Add Campaign
            </a>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($promotions as $promo)
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col group transition-all relative">
            <div class="flex justify-between items-start mb-6">
                <!-- Sparkle Icon from Image -->
                <div class="w-14 h-14 rounded-2xl bg-[#fffcf0] text-[#f59e0b] flex items-center justify-center text-2xl border border-[#fef3c7]">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>

                <!-- Status Badge -->
                @php
                    $isActive = $promo->is_active && $promo->start_date->isPast() && $promo->end_date->isFuture();
                @endphp
                @if($isActive)
                    <span class="px-3 py-1 bg-[#ecfdf5] text-[#10b981] text-[10px] font-black rounded-full uppercase tracking-widest">
                        Active
                    </span>
                @else
                    <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[10px] font-black rounded-full uppercase tracking-widest">
                        Inactive
                    </span>
                @endif
            </div>

            <!-- Title & Type -->
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-1 lowercase">{{ $promo->name }}</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">TYPE: {{ $promo->type }}</p>
            </div>

            <!-- Benefit Box -->
            <div class="bg-[#f8fafc] rounded-[1.5rem] p-6 mb-8 border border-slate-100">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Benefit</p>
                <p class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($promo->discount_percentage, 2) }}% OFF</p>
            </div>

            <!-- Schedule -->
            <div class="space-y-3 mb-10 px-1">
                <div class="flex justify-between items-center text-[12px] uppercase tracking-tighter">
                    <span class="font-bold text-slate-400">Starts</span>
                    <span class="font-black text-slate-900">{{ $promo->start_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between items-center text-[12px] uppercase tracking-tighter">
                    <span class="font-bold text-slate-400">Ends</span>
                    <span class="font-black text-slate-900">{{ $promo->end_date->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mt-auto">
                <a href="{{ route('promotions.edit', $promo->id) }}" class="flex-1 text-center py-4 border-2 border-slate-100 rounded-2xl font-bold text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    Edit Campaign
                </a>
                <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Remove this campaign?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-16 h-14 bg-[#fff1f2] text-[#f43f5e] rounded-2xl flex items-center justify-center border-2 border-[#ffe4e6] hover:bg-[#ffe4e6] transition-colors">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
            <i class="fa-solid fa-receipt text-5xl mb-4 opacity-20"></i>
            <p class="font-black uppercase tracking-widest">No Promotions Found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection