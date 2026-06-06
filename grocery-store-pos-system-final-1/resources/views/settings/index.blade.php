@extends('layouts.app')

@section('title', 'Store Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-200">
                <i class="fas fa-cog"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment Configuration</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Manage checkout options</p>
            </div>
        </div>

        <form action="{{ route('settings.gcash.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div class="space-y-4">
                <label class="text-xs font-black uppercase text-indigo-500 tracking-[0.2em] ml-1 mb-2 block">GCash Payment QR Code</label>

                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-48 h-48 rounded-[2rem] bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden">
                        @if($gcash_qr)
                            <img src="{{ asset('storage/' . $gcash_qr) }}" class="w-full h-full object-contain p-2">
                        @else
                            <i class="fas fa-qrcode text-5xl text-slate-200"></i>
                        @endif
                    </div>

                    <div class="flex-1 space-y-4">
                        <p class="text-sm text-slate-500 leading-relaxed font-medium">
                            Upload your GCash Personal or Merchant QR code here. It will be displayed to the cashier during the checkout process for digital payments.
                        </p>
                        <input type="file" name="gcash_qr" required
                               class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 outline-none transition-all font-bold text-xs">
                        @error('gcash_qr')
                            <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-8 border-t border-slate-50">
                <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-lg shadow-indigo-200 uppercase tracking-widest text-sm hover:bg-indigo-700 transition-all border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1">
                    SAVE CONFIGURATION
                </button>
            </div>
        </form>
    </div>

    <!-- Store Info Edit Card -->
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-900 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-100">
                <i class="fas fa-id-card"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Store Profile</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Update Receipt Information</p>
            </div>
        </div>

        <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] ml-1">Store Legal Name</label>
                    <input type="text" name="store_name" value="{{ $store_name }}" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none font-bold text-slate-800">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] ml-1">Contact Hotline</label>
                    <input type="text" name="store_contact" value="{{ $store_contact }}" required
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none font-bold text-slate-800">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black uppercase text-indigo-500 tracking-[0.2em] ml-1">Business Address</label>
                    <textarea name="store_address" required rows="3"
                           class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none font-bold text-slate-800">{{ $store_address }}</textarea>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="w-full py-5 bg-indigo-900 text-white rounded-2xl font-black shadow-lg shadow-indigo-100 uppercase tracking-widest text-sm hover:bg-slate-800 transition-all border-b-4 border-slate-950 active:border-b-0 active:translate-y-1">
                    UPDATE PROFILE DATA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
