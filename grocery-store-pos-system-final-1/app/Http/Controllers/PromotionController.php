<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('product')->latest()->get();
        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Promotion::create(array_merge($validated, ['type' => 'PRODUCT']));

        return redirect()->route('promotions.index')->with('success', 'Promotion campaign created.');
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::orderBy('name')->get();
        return view('promotions.edit', compact('promotion', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($validated);

        return redirect()->route('promotions.index')->with('success', 'Campaign updated.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Campaign removed.');
    }
}
