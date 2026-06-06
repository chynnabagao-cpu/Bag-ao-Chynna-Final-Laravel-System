<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        $lowStockCount = Product::lowStock()->count();
        return view('inventory.index', compact('products', 'lowStockCount'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'barcode' => 'required|string|unique:products,barcode',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_url' => 'nullable|url',
                'category_id' => 'required|exists:categories,id',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'discount_percentage' => 'nullable|numeric|min:0|max:100',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_threshold' => 'required|integer|min:0',
                'expiration_date' => 'nullable|date',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $validated['image_path'] = $path;
            } elseif ($request->filled('image_url')) {
                $validated['image_path'] = $request->image_url;
            }

            Product::create($validated);
            return redirect()->route('inventory.index')->with('success', 'Product added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Operation failed: ' . $e->getMessage());
        }
    }

    public function edit(Product $inventory)
    {
        $categories = Category::all();
        if($categories->isEmpty()) {
            return redirect()->route('categories.create')->with('error', 'Please add a category before editing products.');
        }
        return view('inventory.edit', ['product' => $inventory, 'categories' => $categories]);
    }

    public function update(Request $request, Product $inventory)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'barcode' => 'required|string|unique:products,barcode,'.$inventory->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'image_url' => 'nullable|url',
                'category_id' => 'required|exists:categories,id',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock_threshold' => 'required|integer|min:0',
                'expiration_date' => 'nullable|date',
            ]);

            if ($request->hasFile('image')) {
                if ($inventory->image_path && !str_starts_with($inventory->image_path, 'http')) {
                    \Storage::disk('public')->delete($inventory->image_path);
                }
                $path = $request->file('image')->store('products', 'public');
                $validated['image_path'] = $path;
            } elseif ($request->filled('image_url')) {
                if ($inventory->image_path && !str_starts_with($inventory->image_path, 'http')) {
                    \Storage::disk('public')->delete($inventory->image_path);
                }
                $validated['image_path'] = $request->image_url;
            }

            // Explicitly handle expiration_date and discount
            $inventory->fill($validated);
            $inventory->expiration_date = $request->filled('expiration_date') ? $request->expiration_date : null;
            $inventory->discount_percentage = $request->discount_percentage ?? 0;
            $inventory->save();

            return redirect()->route('inventory.index')->with('success', 'Inventory updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy(Product $inventory)
    {
        try {
            // Check if product has sales
            $salesCount = \DB::table('sale_items')->where('product_id', $inventory->id)->count();

            if ($salesCount > 0) {
                // If it has sales, we SOFT DELETE it (Model already uses SoftDeletes)
                $inventory->delete();
                return redirect()->route('inventory.index')->with('success', 'Product archived. It will no longer appear in the terminal, but historical records are kept.');
            }

            // If it never had sales, we can delete it permanently
            $inventory->forceDelete();
            return redirect()->route('inventory.index')->with('success', 'Product permanently removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Critical Error: ' . $e->getMessage());
        }
    }
}
