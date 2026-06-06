
@extends('layouts.app')

@section('content')
<div class="flex h-screen gap-6 p-6 overflow-hidden" x-data="posSystem({{ $lastSale ? 'true' : 'false' }})">
    <!-- Receipt Modal -->
    <template x-if="showReceipt">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4 no-print">
            <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full overflow-hidden animate-zoom-in">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-black text-slate-900 uppercase tracking-tight">Transaction Receipt</h3>
                    <button @click="showReceipt = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-8 max-h-[70vh] overflow-y-auto bg-slate-50 flex justify-center">
                    <!-- Thermal Receipt Content -->
                    <div id="thermal-receipt" class="receipt shadow-lg">
                        <div class="text-center mb-4">
                            <h2 class="text-xl font-black uppercase tracking-tighter">{{ \App\Models\Setting::get('store_name', 'FreshMart Enterprise') }}</h2>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">{{ \App\Models\Setting::get('store_address', '123 Market St, Metro Manila') }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">Tel: {{ \App\Models\Setting::get('store_contact', '+63 912 345 6789') }}</p>
                        </div>

                        <div class="receipt-divider"></div>

                        <div class="text-[10px] space-y-1 mb-4">
                            <div class="flex justify-between">
                                <span class="font-bold">OR #:</span>
                                <span class="font-black">{{ $lastSale ? $lastSale->receipt_number : '' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold">Date:</span>
                                <span>{{ $lastSale ? $lastSale->created_at->format('M d, Y H:i') : '' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold">Cashier:</span>
                                <span class="uppercase">{{ $lastSale && $lastSale->cashier ? $lastSale->cashier->name : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="receipt-divider"></div>

                        <table class="w-full text-[10px] mb-4">
                            <thead>
                                <tr class="text-left border-b border-black">
                                    <th class="pb-1 font-black uppercase">Item</th>
                                    <th class="pb-1 text-right font-black uppercase">Qty</th>
                                    <th class="pb-1 text-right font-black uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/10">
                                @if($lastSale)
                                    @foreach($lastSale->items as $item)
                                    <tr>
                                        <td class="py-1">
                                            <div>{{ $item->product->name }}</div>
                                            @if($item->discount_amount > 0)
                                                <div class="text-[8px] italic text-slate-500">(Disc. ₱{{ number_format($item->discount_amount, 2) }})</div>
                                            @endif
                                        </td>
                                        <td class="py-1 text-right">{{ $item->quantity }}</td>
                                        <td class="py-1 text-right">{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div class="receipt-divider"></div>

                        <div class="space-y-1 text-[10px]">
                            <div class="flex justify-between text-base font-black border-b border-black pb-1 mb-1">
                                <span class="uppercase tracking-tighter">Amount Due</span>
                                <span>₱{{ $lastSale ? number_format($lastSale->total_amount, 2) : '0.00' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold uppercase">Payment Mode:</span>
                                <span class="font-black uppercase">{{ $lastSale ? $lastSale->payment_method : '' }}</span>
                            </div>
                            @if($lastSale && $lastSale->payment_method === 'Cash')
                            <div class="flex justify-between">
                                <span class="font-bold uppercase">Cash Rendered:</span>
                                <span>₱{{ number_format($lastSale->cash_received, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-black">
                                <span class="uppercase">Change:</span>
                                <span>₱{{ number_format($lastSale->change_amount, 2) }}</span>
                            </div>
                            @endif
                            
                            @if($lastSale)
                                @php
                                    $totalSavings = $lastSale->items->sum('discount_amount');
                                @endphp
                                @if($totalSavings > 0)
                                    <div class="flex justify-between text-emerald-600 font-bold mt-2 pt-1 border-t border-black/5">
                                        <span class="uppercase">Total Savings:</span>
                                        <span>₱{{ number_format($totalSavings, 2) }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="receipt-divider"></div>

                        <div class="text-center text-[9px] font-bold italic mt-4 space-y-1">
                            <p>Thank you for shopping at FreshMart!</p>
                            <p>Please keep this for your records.</p>
                            <p class="not-italic text-[8px] mt-2 opacity-50">{{ now()->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border-t flex gap-4">
                    <button @click="window.print()" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 uppercase tracking-widest">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                    <button @click="showReceipt = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all uppercase tracking-widest">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </template>
    <div class="flex-1 overflow-y-auto">
        <div class="mb-6 space-y-4">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" x-model="search" placeholder="Search barcode or name..." 
                           class="w-full pl-12 pr-4 py-4 bg-white border-2 border-slate-100 rounded-2xl shadow-sm focus:border-indigo-500 outline-none transition-all font-bold">
                </div>
                <button type="button" @click="toggleScanner()" 
                        :class="isScanning ? 'bg-red-500 shadow-red-100' : 'bg-indigo-600 shadow-indigo-100'"
                        class="px-6 h-14 rounded-2xl text-white flex items-center justify-center shadow-lg transition-all active:scale-95 gap-3">
                    <i class="fas" :class="isScanning ? 'fa-times' : 'fa-barcode'"></i>
                    <span class="font-black text-xs uppercase tracking-widest hidden md:inline" x-text="isScanning ? 'Stop' : 'Scanner'"></span>
                </button>
            </div>

            <!-- Barcode Scanner Camera Preview -->
            <div x-show="isScanning" x-cloak 
                 class="overflow-hidden rounded-[2.5rem] bg-slate-900 border-4 border-indigo-500 shadow-2xl relative transition-all duration-500 max-w-lg mx-auto aspect-video">
                <div id="reader" class="w-full h-full"></div>
                <div class="absolute inset-0 pointer-events-none border-[4rem] border-black/40"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-0.5 bg-red-500 animate-pulse shadow-[0_0_15px_rgba(239,68,68,0.8)]"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($products as $product)
            @php
                $imageUrl = $product->image_path 
                    ? (str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path))
                    : '';
                
                $activePromo = $activePromotions->where('product_id', $product->id)->first();
                $discountPercent = $activePromo ? $activePromo->discount_percentage : $product->discount_percentage;
                $finalPrice = $product->selling_price - ($product->selling_price * ($discountPercent / 100));
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border-2 border-transparent hover:border-indigo-500 cursor-pointer transition-all overflow-hidden group"
                 @click="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $finalPrice }}, '{{ $imageUrl }}')"
                 x-show="matchesSearch('{{ $product->name }}', '{{ $product->barcode }}')">
                
                <div class="h-32 bg-slate-100 relative overflow-hidden">
                    @if($product->image_path)
                        <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-50">
                            <i class="fas fa-image text-3xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        <span class="px-2 py-1 bg-white/90 backdrop-blur shadow-sm rounded-lg text-[10px] font-black text-indigo-600 uppercase">{{ $product->category->name }}</span>
                        
                        <!-- Expiration Status Badge -->
                        @if($product->expiration_date)
                            @php
                                $expiry = \Carbon\Carbon::parse($product->expiration_date);
                                $daysRemaining = now()->diffInDays($expiry, false);
                                
                                $isExpired = $daysRemaining <= 10;
                                $isNear = $daysRemaining > 10 && $daysRemaining <= 30;
                            @endphp
                            @if($isExpired)
                                <span class="px-2 py-1 bg-red-600 text-white shadow-md rounded-lg text-[10px] font-black uppercase animate-pulse">⚠️ EXPIRED</span>
                            @elseif($isNear)
                                <span class="px-2 py-1 bg-amber-500 text-white shadow-md rounded-lg text-[10px] font-black uppercase">⌛ SOON</span>
                            @endif
                        @endif

                        @if($discountPercent > 0)
                            <span class="px-2 py-1 {{ $activePromo ? 'bg-indigo-600 shadow-indigo-200' : 'bg-red-500 shadow-red-200' }} text-white shadow-md rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                {{ number_format($discountPercent, 0) }}% OFF {{ $activePromo ? '• PROMO' : '' }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="font-bold text-slate-800 text-sm line-clamp-1 mb-1">{{ $product->name }}</h3>
                    <div class="flex justify-between items-end">
                        <div>
                            @if($discountPercent > 0)
                                <p class="text-[10px] text-slate-400 line-through">₱{{ number_format($product->selling_price, 2) }}</p>
                            @endif
                            <p class="text-lg font-black text-slate-900 leading-none">₱{{ number_format($finalPrice, 2) }}</p>
                        </div>
                        <p class="text-[10px] font-bold {{ $product->stock_quantity < 10 ? 'text-amber-500' : 'text-slate-400' }}">Stock: {{ $product->stock_quantity }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Sidebar Cart -->
    <div class="w-96 bg-white rounded-2xl shadow-lg border border-slate-200 flex flex-col overflow-hidden">
        <div class="p-4 border-b bg-slate-50">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i> Current Cart
            </h2>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <template x-for="item in cart" :key="item.id">
                <div class="flex gap-3 items-center border-b border-slate-50 pb-3">
                    <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
                        <template x-if="item.image">
                            <img :src="item.image" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!item.image">
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <i class="fas fa-image"></i>
                            </div>
                        </template>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm text-slate-800 line-clamp-1" x-text="item.name"></p>
                        <p class="text-xs text-indigo-600 font-black">₱<span x-text="item.price.toFixed(2)"></span></p>
                    </div>
                    <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-100">
                        <button type="button" @click="updateQty(item.id, -1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-slate-600 hover:bg-slate-50">-</button>
                        <span x-text="item.quantity" class="w-6 text-center text-xs font-black text-slate-900"></span>
                        <button type="button" @click="updateQty(item.id, 1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-lg shadow-sm text-slate-600 hover:bg-slate-50">+</button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Checkout Form (No API - Uses standard POST) -->
        <form action="{{ route('pos.checkout') }}" method="POST" class="p-6 bg-white border-t space-y-6">
            @csrf
            <input type="hidden" name="cart_items" :value="JSON.stringify(cart)">
            
            <div class="space-y-4">
                <div class="flex justify-between items-end">
                    <span class="text-xs font-black uppercase text-slate-400">Grand Total</span>
                    <span class="text-3xl font-black text-slate-900 tracking-tighter">₱<span x-text="total.toFixed(2)"></span></span>
                </div>

                <div class="h-px bg-slate-100"></div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Payment Strategy</label>
                    <select name="payment_method" x-model="paymentMethod" class="w-full p-4 rounded-2xl border-2 border-slate-100 font-bold text-slate-700 bg-slate-50 focus:bg-white focus:border-indigo-500 transition-all outline-none">
                        <option value="Cash">💵 Cash Payment</option>
                        <option value="GCash">📱 GCash Payment</option>
                    </select>
                </div>

                <div x-show="paymentMethod === 'Cash'" x-transition x-cloak class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Cash Received</label>
                        <template x-if="cashReceived > 0 && cashReceived < total">
                            <span class="text-[10px] font-black text-red-500 uppercase animate-pulse">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Insufficient Amount
                            </span>
                        </template>
                    </div>
                    <input type="number" name="cash_received" x-model="cashReceived" 
                           :class="cashReceived > 0 && cashReceived < total ? 'border-red-500 bg-red-50' : 'border-slate-100 bg-slate-50'"
                           class="w-full p-5 text-3xl font-black border-2 rounded-2xl focus:bg-white focus:border-indigo-500 transition-all outline-none">
                    
                    <template x-if="cashReceived >= total && total > 0">
                        <div class="flex justify-between items-center p-4 bg-emerald-50 rounded-2xl border-2 border-emerald-100">
                            <span class="text-[10px] font-black uppercase text-emerald-600">Change Due</span>
                            <span class="text-xl font-black text-emerald-700">₱<span x-text="(cashReceived - total).toFixed(2)"></span></span>
                        </div>
                    </template>
                </div>

                <!-- GCash QR -->
                <div x-show="paymentMethod === 'GCash'" x-transition x-cloak class="space-y-4 p-4 bg-indigo-50 rounded-2xl border-2 border-indigo-100 flex flex-col items-center">
                    <p class="text-[10px] font-black uppercase text-indigo-600 tracking-widest text-center">Scan to Pay via GCash</p>
                    <div class="w-40 h-40 bg-white rounded-xl shadow-inner flex items-center justify-center p-2 border border-indigo-200">
                        @if($gcash_qr)
                            <img src="{{ asset('storage/' . $gcash_qr) }}" class="w-full h-full object-contain">
                        @else
                            <div class="text-center text-slate-300">
                                <i class="fas fa-qrcode text-6xl mb-2"></i>
                                <p class="text-[10px] font-bold">QR NOT SET</p>
                            </div>
                        @endif
                    </div>
                    <p class="text-[10px] font-bold text-indigo-400 text-center italic leading-tight">Please confirm transaction on terminal after customer scans.</p>
                </div>
            </div>

            <button type="submit" :disabled="cart.length === 0 || (paymentMethod === 'Cash' && cashReceived < total)" 
                    class="w-full bg-indigo-600 text-white py-5 rounded-[1.5rem] font-black text-lg hover:bg-indigo-700 disabled:opacity-30 disabled:grayscale transition-all shadow-xl shadow-indigo-100 border-b-4 border-indigo-800 active:border-0 active:translate-y-1">
                COMPLETE TRANSACTION
            </button>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function posSystem(initialShowReceipt) {
    return {
        showReceipt: initialShowReceipt,
        isScanning: false,
        html5QrCode: null,
        cart: [],
        search: '',
        paymentMethod: 'Cash',
        cashReceived: 0,
        products: @json($products),
        
        addToCart(id, name, price, image) {
            // Find full product info to check expiry
            const product = this.products.find(p => p.id === id);
            if (product && product.expiration_date) {
                const expiryDate = new Date(product.expiration_date);
                if (expiryDate < new Date().setHours(0,0,0,0)) {
                    if(!confirm(`⚠️ CAUTION: ${name} is EXPIRED. Are you sure you want to add this to the cart?`)) {
                        return;
                    }
                }
            }

            let existing = this.cart.find(i => i.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({ id, name, price, image, quantity: 1 });
            }
            
            // Audio feedback for scan/add
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');
            audio.play().catch(() => {});
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
                fps: 15, 
                qrbox: { width: 300, height: 200 },
                aspectRatio: 1.777778
            };

            const successCallback = (decodedText) => {
                // Find product by barcode
                const product = this.products.find(p => p.barcode === decodedText);
                
                if (product) {
                    let imgUrl = '';
                    if (product.image_path) {
                        imgUrl = product.image_path.startsWith('http') 
                            ? product.image_path 
                            : `{{ asset('storage') }}/${product.image_path}`;
                    }
                    this.addToCart(product.id, product.name, product.selling_price, imgUrl);
                    
                    // Haptic feedback
                    if (window.navigator.vibrate) window.navigator.vibrate(100);
                } else {
                    alert(`Product with barcode ${decodedText} not found in inventory.`);
                }
            };

            this.html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                successCallback
            ).catch(err => {
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
            }
        },

        updateQty(id, delta) {
            let item = this.cart.find(i => i.id === id);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) this.cart = this.cart.filter(i => i.id !== id);
            }
        },

        get total() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        matchesSearch(name, barcode) {
            if (!this.search) return true;
            return name.toLowerCase().includes(this.search.toLowerCase()) || barcode.includes(this.search);
        }
    }
}
</script>
@endsection
