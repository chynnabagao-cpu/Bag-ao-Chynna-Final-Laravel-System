<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'today');
        $now = Carbon::now();

        $startDate = match($range) {
            'week' => $now->startOfWeek()->copy(),
            'month' => $now->startOfMonth()->copy(),
            default => $now->startOfDay()->copy(),
        };
        $endDate = Carbon::now();

        // Metrics based on range
        $totalRevenue = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalOrders = Sale::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Global inventory stats
        $totalProducts = Product::count();
        $lowStockItems = Product::lowStock()->count();
        $expiringItems = Product::whereNotNull('expiration_date')
            ->where('expiration_date', '<=', Carbon::now()->addDays(10))
            ->count();

        // Best Sellers (By Quantity) - filtered by range
        $bestSellers = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with(['product' => function($query) {
                $query->withTrashed();
            }])
            ->limit(5)
            ->get();

        // Recent Transactions
        $recentSales = Sale::with('cashier')->latest()->limit(5)->get();

        return view('dashboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalProducts', 
            'lowStockItems',
            'expiringItems',
            'bestSellers',
            'recentSales',
            'range'
        ));
    }
}

