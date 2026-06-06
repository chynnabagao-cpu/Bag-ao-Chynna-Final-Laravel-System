<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:Cash,GCash',
            'cash_received' => 'required_if:payment_method,Cash|numeric',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $totalAmount = 0;
            $saleItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $subtotal = $product->selling_price * $item['quantity'];
                $totalAmount += $subtotal;

                // Update Stock
                $product->decrement('stock_quantity', $item['quantity']);

                $saleItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ];
            }

            $changeAmount = $request->payment_method === 'Cash'
                ? $request->cash_received - $totalAmount
                : 0;

            $sale = Sale::create([
                'receipt_number' => 'REC-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id() ?? 1,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'cash_received' => $request->cash_received,
                'change_amount' => $changeAmount,
            ]);

            $sale->items()->createMany($saleItems);

            return response()->json([
                'message' => 'Sale processed successfully',
                'sale' => $sale->load('items.product')
            ], 201);
        });
    }

    public function index() {
        return Sale::with(['items.product', 'user'])->latest()->paginate(20);
    }
}
