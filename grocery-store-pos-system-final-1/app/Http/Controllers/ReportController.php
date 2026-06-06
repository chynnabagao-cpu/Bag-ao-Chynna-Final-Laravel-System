<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'daily');
        $data = $this->calculateReportData($range);

        return view('reports.index', array_merge($data, ['range' => $range]));
    }

    /**
     * AJAX Endpoint for Real-Time Updates
     */
    public function stats(Request $request)
    {
        $range = $request->get('range', 'daily');
        return response()->json($this->calculateReportData($range));
    }

    private function calculateReportData($range)
    {
        $now = Carbon::now();
        $startDate = match($range) {
            'weekly' => $now->startOfWeek()->copy(),
            'monthly' => $now->startOfMonth()->copy(),
            default => $now->startOfDay()->copy(),
        };
        $endDate = Carbon::now();

        $sales = Sale::with(['cashier', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $transactionCount = $sales->count();

        $totalCost = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->select(DB::raw('SUM(sale_items.quantity * products.cost_price) as total_cost'))
            ->first()->total_cost ?? 0;

        $estimatedProfit = $totalRevenue - $totalCost;

        // Chart Data: Hourly for day, Daily for week/month
        $format = $range === 'daily' ? '%H:00' : '%Y-%m-%d';
        $chartData = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '$format') as period"),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $inventoryValueCost = Product::select(DB::raw('SUM(stock_quantity * cost_price) as val'))->first()->val ?? 0;
        $inventoryValueRetail = Product::select(DB::raw('SUM(stock_quantity * selling_price) as val'))->first()->val ?? 0;
        $lowStockProducts = Product::lowStock()->with('category')->get();

        return [
            'sales' => $sales,
            'totalRevenue' => (float)$totalRevenue,
            'estimatedProfit' => (float)$estimatedProfit,
            'transactionCount' => $transactionCount,
            'inventoryValueCost' => (float)$inventoryValueCost,
            'inventoryValueRetail' => (float)$inventoryValueRetail,
            'chartLabels' => $chartData->pluck('period'),
            'chartValues' => $chartData->pluck('revenue'),
            'lowStockProducts' => $lowStockProducts,
            'lastUpdated' => now()->format('H:i:s')
        ];
    }

    /**
     * Fully Functional Export System (PDF & CSV)
     */
    public function export(Request $request, $type)
    {
        $range = $request->get('range', 'daily');
        $data = $this->calculateReportData($range);
        $sales = $data['sales'];

        if ($type === 'pdf') {
            // Returns a dedicated print-optimized view for formal PDF generation
            return view('reports.export', array_merge($data, ['range' => $range]));
        }

        // CSV Export Logic
        $filename = "FreshMart_Report_" . $range . "_" . now()->format('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Receipt #', 'Date', 'Time', 'Cashier', 'Method', 'Total Amount'];

        $callback = function() use($sales, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->receipt_number,
                    $sale->created_at->format('Y-m-d'),
                    $sale->created_at->format('H:i A'),
                    $sale->cashier->name,
                    $sale->payment_method,
                    number_format($sale->total_amount, 2),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
