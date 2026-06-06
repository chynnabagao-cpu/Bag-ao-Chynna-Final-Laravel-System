@extends('layouts.app')

@section('title', 'Edit Staff Member')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl shadow-lg shadow-amber-100">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Modify Account</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Operator: {{ $user->username }}</p>
            </div>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] ml-1 mb-2 block">Full Legal Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Terminal Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">System Role</label>
                    <select name="role" required
                            class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                        <option value="Cashier" {{ $user->role === 'Cashier' ? 'selected' : '' }}>Cashier (Terminal Only)</option>
                        <option value="Admin" {{ $user->role === 'Admin' ? 'selected' : '' }}>Administrator (Full Access)</option>
                    </select>
                </div>

                <div class="col-span-2 p-6 bg-amber-50 rounded-3xl border-2 border-amber-100 space-y-4 mt-2">
                    <p class="text-[10px] font-black uppercase text-amber-600 tracking-widest text-center">Security Update</p>
                    <p class="text-[10px] text-amber-500 font-bold text-center leading-relaxed">Leave password fields empty if you do not wish to change the access pin.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">New Access Pin</label>
                            <input type="password" name="password"
                                   class="w-full p-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] ml-1 mb-2 block">Confirm New Pin</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full p-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-indigo-500 outline-none transition-all font-bold text-slate-800">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-8">
                <a href="{{ route('users.index') }}" class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-slate-200 transition-colors">
                    BACK
                </a>
                <button type="submit" class="flex-2 px-12 py-5 bg-amber-500 text-white rounded-2xl font-black shadow-lg shadow-amber-100 uppercase tracking-widest text-sm hover:bg-amber-600 transition-all border-b-4 border-amber-800 active:border-b-0 active:translate-y-1">
                    UPDATE ACCOUNT
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
