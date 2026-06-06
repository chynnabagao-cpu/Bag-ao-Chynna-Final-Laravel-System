@extends('layouts.app')

@section('title', 'New Staff Account')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-200">
                <i class="fas fa-user-plus"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Create Account</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Access & Permissions</p>
            </div>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] ml-1 mb-2 block">Full Legal Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Terminal Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">System Role</label>
                    <select name="role" required
                            class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                        <option value="Cashier">Cashier (Terminal Only)</option>
                        <option value="Admin">Administrator (Full Access)</option>
                    </select>
                </div>

                <div class="h-px bg-slate-100 col-span-2 my-2"></div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Access Pin (Password)</label>
                    <input type="password" name="password" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Confirm Pin</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                </div>
            </div>

            <div class="flex gap-4 pt-8">
                <a href="{{ route('users.index') }}" class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-slate-200 transition-colors">
                    BACK
                </a>
                <button type="submit" class="flex-2 px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-lg shadow-indigo-200 uppercase tracking-widest text-sm hover:bg-indigo-700 transition-all border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1">
                    CREATE ACCOUNT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
