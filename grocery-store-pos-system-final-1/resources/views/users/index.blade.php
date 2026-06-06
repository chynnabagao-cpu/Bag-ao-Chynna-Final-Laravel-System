@extends('layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Terminal Operators</h1>
            <p class="text-slate-500 font-medium">Manage user accounts and terminal permissions</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('users.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center gap-2">
                <i class="fas fa-user-plus"></i> ADD STAFF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between group hover:shadow-xl hover:shadow-indigo-500/5 transition-all">
            <div>
                <div class="flex justify-between items-start mb-6">
                    <div class="w-16 h-16 rounded-full bg-indigo-50 border-2 border-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-black shadow-inner">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Remove this staff member?')">
                            @csrf @method('DELETE')
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight mb-1">{{ $user->name }}</h3>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">@ {{ $user->username }}</span>
                    <span class="px-2 py-0.5 {{ $user->role === 'Admin' ? 'bg-indigo-600 text-white' : 'bg-emerald-500 text-white' }} text-[9px] font-black rounded-full uppercase">
                        {{ $user->role }}
                    </span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-slate-50 flex justify-between items-center text-[10px] font-bold text-slate-400">
                <span>Created {{ $user->created_at->format('M Y') }}</span>
                @if($user->id === auth()->id())
                    <span class="text-indigo-500 uppercase font-black tracking-widest">You</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
