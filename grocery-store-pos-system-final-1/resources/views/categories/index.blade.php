@extends('layouts.app')

@section('title', 'Category Management')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Product Categories</h1>
            <p class="text-slate-500 font-medium">Organize your inventory with custom categories</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('categories.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> NEW CATEGORY
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-2xl shadow-lg flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between group hover:shadow-xl hover:shadow-indigo-500/5 transition-all">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('categories.edit', $category->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-1">{{ $category->name }}</h3>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $category->products_count }} Products Attached</p>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-50">
                <a href="{{ route('inventory.index', ['category' => $category->id]) }}" class="text-indigo-600 text-xs font-black uppercase tracking-[0.2em] hover:text-indigo-800 transition-colors">
                    View Inventory <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @endforeach

        @if($categories->isEmpty())
        <div class="col-span-full py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
            <i class="fas fa-tags text-5xl mb-4 opacity-20"></i>
            <p class="font-black uppercase tracking-widest">No Categories Defined</p>
            <p class="text-sm mt-1">Start by adding your first product category</p>
        </div>
        @endif
    </div>
</div>
@endsection
