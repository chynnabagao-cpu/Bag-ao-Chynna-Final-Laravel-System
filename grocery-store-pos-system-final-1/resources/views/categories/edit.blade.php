@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl shadow-lg shadow-amber-100">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Edit Category</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Update Identification</p>
            </div>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            <div>
                <label class="text-xs font-black uppercase text-amber-600 tracking-[0.2em] ml-1 mb-2 block">Category Name</label>
                <input type="text" name="name" value="{{ $category->name }}" required
                       class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/5 outline-none transition-all font-black text-slate-800"
                       placeholder="Category Name">
                @error('name')
                    <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4 pt-4">
                <a href="{{ route('categories.index') }}" class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-slate-200 transition-colors">
                    BACK
                </a>
                <button type="submit" class="flex-1 py-5 bg-amber-500 text-white rounded-2xl font-black shadow-lg shadow-amber-100 uppercase tracking-widest text-sm hover:bg-amber-600 transition-all border-b-4 border-amber-700 active:border-b-0 active:translate-y-1">
                    UPDATE
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
