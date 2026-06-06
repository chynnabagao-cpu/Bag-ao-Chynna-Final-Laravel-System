@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto" x-data="productForm('{{ $product->image_path ? (str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path)) : '' }}')">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-xl shadow-lg shadow-amber-100">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Edit Product</h2>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Update SKU: {{ $product->barcode }}</p>
                </div>
            </div>
            <a href="{{ route('inventory.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </a>
        </div>

        <form action="{{ route('inventory.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Visual Identity Section -->
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-amber-600 tracking-widest ml-1 mb-3 block">Identity Preview</label>
                        <div class="aspect-square rounded-[2.5rem] bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center relative group shadow-inner">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-6">
                                    <i class="fas fa-image text-5xl text-slate-200 mb-3"></i>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">No Preview Available</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Replace File</label>
                            <input type="file" name="image" @change="previewFile"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-amber-50 file:text-amber-600 hover:file:bg-amber-100 transition-all cursor-pointer bg-slate-50 p-2 rounded-2xl border-2 border-slate-100 border-dashed">
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-slate-100"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-2 text-[10px] font-black text-slate-300 uppercase italic">or use</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Photo Image URL</label>
                            <input type="url" name="image_url" x-model="imageUrl" @input="previewUrl"
                                   value="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : '' }}"
                                   placeholder="https://example.com/photo.jpg"
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-amber-500 outline-none transition-all font-bold text-xs">
                        </div>
                    </div>
                </div>

                <!-- Product Details Section -->
                <div class="md:col-span-2 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-2 block">Product Name</label>
                            <input type="text" name="name" value="{{ $product->name }}" required
                                   class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] focus:bg-white focus:border-indigo-500 outline-none transition-all font-black text-slate-800 shadow-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Barcode / SKU</label>
                            <input type="text" name="barcode" value="{{ $product->barcode }}" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Category</label>
                            <select name="category_id" required
                                    class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Cost Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">₱</span>
                                <input type="number" step="0.01" name="cost_price" value="{{ $product->cost_price }}" required
                                       class="w-full p-4 pl-8 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Selling Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">₱</span>
                                <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price }}" required
                                       class="w-full p-4 pl-8 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Stock Quantity</label>
                            <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Min. Alert Level</label>
                            <input type="number" name="min_stock_threshold" value="{{ $product->min_stock_threshold }}" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-2 block">Discount (%)</label>
                            <input type="number" step="0.01" name="discount_percentage" value="{{ $product->discount_percentage }}"
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-2 block">Expiration Date</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" name="expiration_date" value="{{ $product->expiration_date ? $product->expiration_date->format('Y-m-d') : '' }}"
                                       class="w-full p-4 pl-12 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm text-slate-800">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-8 border-t border-slate-50">
                <a href="{{ route('inventory.index') }}" class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-slate-200 transition-colors">
                    DISCARD CHANGES
                </a>
                <button type="submit" class="flex-2 px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-lg shadow-indigo-200 uppercase tracking-widest text-sm hover:bg-indigo-700 transition-all border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1">
                    CONFIRM UPDATES
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function productForm(initialImage) {
    return {
        imagePreview: initialImage,
        imageUrl: initialImage.startsWith('http') ? initialImage : '',

        previewFile(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageUrl = '';
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        previewUrl() {
            if (this.imageUrl) {
                this.imagePreview = this.imageUrl;
            } else {
                this.imagePreview = '{{ $product->image_path && !str_starts_with($product->image_path, "http") ? asset("storage/" . $product->image_path) : "" }}';
            }
        }
    }
}
</script>
@endsection
