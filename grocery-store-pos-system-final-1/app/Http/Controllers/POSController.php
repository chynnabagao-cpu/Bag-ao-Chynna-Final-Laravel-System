<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    /**
     * Show the POS Terminal interface.
     * We pass the products directly to the view.
     */
    public function index()
    {
        $products = Product::with('category')->where('stock_quantity', '>', 0)->get();
        $categories = \App\Models\Category::all();
        $gcash_qr = \App\Models\Setting::get('gcash_qr_path');
        
        // Fetch active promotions
        $activePromotions = \App\Models\Promotion::active()->get();

        $lastSale = null;
        if (session('success_sale')) {
            $lastSale = Sale::with(['items.product', 'cashier'])->find(session('success_sale'));
        }

        return view('pos.index', compact('products', 'categories', 'gcash_qr', 'lastSale', 'activePromotions'));
    }

    /**
     * Handle the Terminal checkout submission.
     * This is a standard POST request from a Blade form.
     */
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'cart_items' => 'required|json',
                'payment_method' => 'required|in:Cash,GCash',
                'cash_received' => 'nullable|numeric|min:0',
            ]);

            $cartData = json_decode($request->cart_items, true);

            if (empty($cartData)) {
                return back()->with('error', 'Cannot process an empty cart.');
            }

            return DB::transaction(function () use ($request, $cartData) {
                $total = 0;
                $now = now();
                
                // Fetch active promotions once
                $activePromotions = \App\Models\Promotion::where('is_active', true)
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->get();

                // 1. Create the Sale (Temporary Total)
                $sale = Sale::create([
                    'receipt_number' => 'REC-' . strtoupper(Str::random(8)),
                    'user_id' => auth()->id(),
                    'total_amount' => 0,
                    'payment_method' => $request->payment_method,
                    'cash_received' => $request->cash_received ?? 0,
                ]);

                foreach ($cartData as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['id']);
                    
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}. Remaining: {$product->stock_quantity}");
                    }

                    // CALCULATE DISCOUNT
                    $promo = $activePromotions->where('product_id', $product->id)->first();
                    $discountPercentage = $promo ? $promo->discount_percentage : $product->discount_percentage;
                    
                    $originalPrice = $product->selling_price;
                    $discountAmountPerUnit = $originalPrice * ($discountPercentage / 100);
                    $finalUnitPrice = $originalPrice - $discountAmountPerUnit;
                    
                    $subtotal = $finalUnitPrice * $item['quantity'];
                    $total += $subtotal;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'original_price' => $originalPrice,
                        'unit_price' => $finalUnitPrice,
                        'discount_amount' => $discountAmountPerUnit * $item['quantity'],
                        'subtotal' => $subtotal,
                    ]);

                    $product->decrement('stock_quantity', $item['quantity']);
                }

                // Final Validation
                if ($request->payment_method === 'Cash' && ($request->cash_received < $total)) {
                    throw new \Exception("Payment failed: Cash received is less than total amount.");
                }

                $sale->update([
                    'total_amount' => $total,
                    'change_amount' => $request->payment_method === 'Cash' ? ($request->cash_received - $total) : 0,
                ]);

                return redirect()->route('pos.index')->with('success_sale', $sale->id)->with('success', 'Transaction completed successfully!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terminal Error: ' . $e->getMessage());
        }
    }
}
