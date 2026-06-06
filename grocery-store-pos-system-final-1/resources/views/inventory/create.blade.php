@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="max-w-4xl mx-auto" x-data="productForm()">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-200">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Create Product</h2>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Inventory Catalog</p>
            </div>
        </div>

        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Visual Identity Section -->
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-3 block">Live Preview</label>
                        <div class="aspect-square rounded-[2.5rem] bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center relative group">
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
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Upload File</label>
                            <input type="file" name="image" @change="previewFile"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer bg-slate-50 p-2 rounded-2xl border-2 border-slate-100 border-dashed">
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
                                   placeholder="https://example.com/photo.jpg"
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-xs">
                        </div>
                    </div>
                </div>

                <!-- Product Details Section -->
                <div class="md:col-span-2 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-2 block">Product Name</label>
                            <input type="text" name="name" required placeholder="Enter product name..."
                                   class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] focus:bg-white focus:border-indigo-500 outline-none transition-all font-black text-slate-800 shadow-sm">
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Barcode / SKU</label>
                            <div class="flex gap-2">
                                <input type="text" name="barcode" x-model="barcode" required
                                       class="flex-1 p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                                <button type="button" @click="toggleScanner()"
                                        :class="isScanning ? 'bg-red-500 shadow-red-100' : 'bg-indigo-600 shadow-indigo-100'"
                                        class="w-14 h-14 rounded-2xl text-white flex items-center justify-center shadow-lg transition-all active:scale-95">
                                    <i class="fas" :class="isScanning ? 'fa-times' : 'fa-barcode'"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Category</label>
                            <select name="category_id" required
                                    class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Barcode Scanner Preview -->
                        <div class="col-span-2 overflow-hidden rounded-[2rem] bg-slate-900 border-4 border-indigo-500 shadow-2xl relative"
                             x-show="isScanning" x-cloak style="aspect-ratio: 16/9;">
                            <div id="reader" class="w-full h-full"></div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Cost Price</label>
                            <input type="number" step="0.01" name="cost_price" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Selling Price</label>
                            <input type="number" step="0.01" name="selling_price" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Initial Stock</label>
                            <input type="number" name="stock_quantity" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1 mb-2 block">Min. Alert Level</label>
                            <input type="number" name="min_stock_threshold" value="10" required
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-2 block">Discount (%)</label>
                            <input type="number" step="0.01" name="discount_percentage" value="0"
                                   class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="text-[10px] font-black uppercase text-indigo-500 tracking-widest ml-1 mb-2 block">Expiration Date</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="date" name="expiration_date"
                                       class="w-full p-4 pl-12 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:bg-white focus:border-indigo-500 outline-none transition-all font-bold text-sm text-slate-800">
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 ml-1 italic font-medium">Leave empty for non-perishable items like bottled water or soda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-8 border-t border-slate-50">
                <a href="{{ route('inventory.index') }}" class="flex-1 text-center py-5 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-slate-200 transition-colors">
                    CANCEL
                </a>
                <button type="submit" class="flex-2 px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black shadow-lg shadow-indigo-200 uppercase tracking-widest text-sm hover:bg-indigo-700 transition-all border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1">
                    SAVE PRODUCT TO CATALOG
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function productForm() {
    return {
        barcode: '',
        isScanning: false,
        html5QrCode: null,
        imagePreview: null,
        imageUrl: '',

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
            }
        },

        toggleScanner() {
            if (this.isScanning) {
                this.stopScanner();
            } else {
                this.startScanner();
            }
        },

        startScanner() {
            this.isScanning = true;
            this.html5QrCode = new Html5Qrcode("reader");

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 150 },
                aspectRatio: 1.777778
            };

            const successCallback = (decodedText) => {
                this.barcode = decodedText;
                this.stopScanner();
                if (window.navigator.vibrate) window.navigator.vibrate(100);
            };

            this.html5QrCode.start(
                { facingMode: "environment" },
                config,
                successCallback
            ).catch((err) => {
                console.error(err);
                this.isScanning = false;
            });
        },

        stopScanner() {
            if (this.html5QrCode) {
                this.html5QrCode.stop().then(() => {
                    this.isScanning = false;
                    this.html5QrCode = null;
                });
            } else {
                this.isScanning = false;
            }
        }
    }
}
</script>
@endsection
