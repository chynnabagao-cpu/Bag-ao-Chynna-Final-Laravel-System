
@extends('layouts.app')

@section('title', 'Financial Reports')

@section('content')
<div class="space-y-8" x-data="realtimeReports('{{ $range }}')">
    <!-- Receipt View Modal -->
    <template x-if="showReceiptModal">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4 no-print">
            <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full overflow-hidden animate-zoom-in">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="font-black text-slate-900 uppercase tracking-tight">Receipt Details</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest" x-text="selectedSale.receipt_number"></p>
                    </div>
                    <button @click="showReceiptModal = false" class="w-10 h-10 rounded-xl bg-white text-slate-400 hover:text-slate-600 shadow-sm flex items-center justify-center border border-slate-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-8 max-h-[60vh] overflow-y-auto bg-slate-50 flex justify-center">
                    <!-- Thermal Receipt Content -->
                    <div id="thermal-receipt" class="receipt shadow-lg border border-slate-100">
                        <div class="text-center mb-4">
                            <h2 class="text-xl font-black uppercase tracking-tighter">{{ \App\Models\Setting::get('store_name', 'FreshMart Enterprise') }}</h2>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">{{ \App\Models\Setting::get('store_address', '123 Market St, Metro Manila') }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase">Tel: {{ \App\Models\Setting::get('store_contact', '+63 912 345 6789') }}</p>
                        </div>

                        <div class="receipt-divider"></div>

                        <div class="text-[10px] space-y-1 mb-4">
                            <div class="flex justify-between">
                                <span class="font-bold">OR #:</span>
                                <span class="font-black" x-text="selectedSale.receipt_number"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold">Date:</span>
                                <span x-text="formatFullDate(selectedSale.created_at)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold">Cashier:</span>
                                <span class="uppercase" x-text="selectedSale.cashier.name"></span>
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
                                <template x-for="item in selectedSale.items" :key="item.id">
                                    <tr>
                                        <td class="py-1" x-text="item.product.name"></td>
                                        <td class="py-1 text-right" x-text="item.quantity"></td>
                                        <td class="py-1 text-right" x-text="parseFloat(item.subtotal).toFixed(2)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <div class="receipt-divider"></div>

                        <div class="space-y-1 text-[10px]">
                            <div class="flex justify-between text-base font-black border-b border-black pb-1 mb-1">
                                <span class="uppercase tracking-tighter">Amount Due</span>
                                <span>₱<span x-text="parseFloat(selectedSale.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2})"></span></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold uppercase">Payment Mode:</span>
                                <span class="font-black uppercase" x-text="selectedSale.payment_method"></span>
                            </div>
                            <template x-if="selectedSale.payment_method === 'Cash'">
                                <div>
                                    <div class="flex justify-between">
                                        <span class="font-bold uppercase">Cash Rendered:</span>
                                        <span>₱<span x-text="parseFloat(selectedSale.cash_received).toFixed(2)"></span></span>
                                    </div>
                                    <div class="flex justify-between font-black">
                                        <span class="uppercase">Change:</span>
                                        <span>₱<span x-text="parseFloat(selectedSale.change_amount).toFixed(2)"></span></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="receipt-divider"></div>

                        <div class="text-center text-[9px] font-bold italic mt-4">
                            <p>Thank you for shopping at FreshMart!</p>
                            <p class="not-italic text-[8px] mt-2 opacity-50">Transaction Audit Copy</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border-t flex gap-4">
                    <button @click="window.print()" class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 uppercase tracking-widest">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button @click="showReceiptModal = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all uppercase tracking-widest">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Header & Range Selector -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Analytics Dashboard</h1>
            <div class="flex items-center gap-2">
                <p class="text-slate-500 font-medium tracking-tight">Detailed performance metrics</p>
                <div class="flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">
                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-[9px] font-black uppercase tracking-widest">Live Syncing</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200">
            @foreach(['daily' => 'Today', 'weekly' => 'This Week', 'monthly' => 'This Month'] as $key => $label)
                <a href="{{ route('reports.index', ['range' => $key]) }}"
                   class="px-5 py-2 rounded-xl text-sm font-bold transition-all {{ $range == $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Financial Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Revenue Card (Expanded) -->
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group md:col-span-1">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform">
                <i class="fas fa-coins text-8xl text-indigo-600"></i>
            </div>
            <p class="text-xs font-black text-indigo-500 uppercase tracking-[0.2em] mb-2">Gross Total Revenue</p>
            <h3 class="text-5xl font-black text-slate-900 leading-none tracking-tighter">₱<span x-text="stats.totalRevenue.toLocaleString(undefined, {minimumFractionDigits: 2})"></span></h3>
            <div class="mt-6 flex items-center gap-4">
                <div class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-black text-xs border border-emerald-100 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span x-text="stats.transactionCount"></span> COMPLETED SALES
                </div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    SYNCED AT <span x-text="stats.lastUpdated"></span>
                </div>
            </div>
        </div>

        <!-- Secondary Metric / Quick Info -->
        <div class="bg-indigo-900 p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden text-white md:col-span-1">
            <div class="absolute -bottom-10 -right-10 opacity-10 rotate-12">
                <i class="fas fa-store text-[12rem]"></i>
            </div>
            <p class="text-xs font-black text-indigo-300 uppercase tracking-[0.2em] mb-2">Terminal Status</p>
            <h3 class="text-3xl font-black mb-4">Operations Active</h3>
            <div class="space-y-3 relative z-10">
                <div class="flex justify-between items-center py-2 border-b border-white/10 text-sm">
                    <span class="font-bold text-indigo-200">Date Range</span>
                    <span class="font-black uppercase" x-text="range"></span>
                </div>
                <div class="flex justify-between items-center py-2 text-sm">
                    <span class="font-bold text-indigo-200">Export Readiness</span>
                    <span class="font-black text-emerald-400">STABLE</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Alerts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Trend Chart -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h3 class="font-black text-slate-800 uppercase tracking-tight flex items-center gap-2 mb-6">
                <i class="fas fa-chart-area text-indigo-500"></i> Revenue Trend
            </h3>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-amber-500"></i> Stock Alerts
                </h3>
                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-full uppercase border border-amber-100">
                    <span x-text="stats.lowStockProducts.length"></span> Items Low
                </span>
            </div>
            <div class="flex-1 overflow-y-auto max-h-64">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="product in stats.lowStockProducts" :key="product.id">
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="px-8 py-4">
                                    <p class="font-bold text-slate-800 text-sm" x-text="product.name"></p>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest" x-text="product.category.name"></p>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <p class="text-xs font-black text-red-600 uppercase">Only <span x-text="product.stock_quantity"></span> Left</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">Threshold: <span x-text="product.min_stock_threshold"></span></p>
                                </td>
                            </tr>
                        </template>
                        <template x-if="stats.lowStockProducts.length === 0">
                            <tr>
                                <td colspan="2" class="py-20 text-center opacity-20">
                                    <i class="fas fa-check-double text-4xl mb-2"></i>
                                    <p class="text-xs font-black uppercase">Stock Levels Healthy</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-black text-slate-800 uppercase tracking-tight flex items-center gap-2">
                <i class="fas fa-receipt text-indigo-500"></i> Transaction History
            </h3>
            <div class="flex gap-2">
                <a href="{{ route('reports.export', 'pdf') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black hover:bg-slate-50 transition-all">
                    <i class="fas fa-file-pdf mr-1 text-red-500"></i> EXPORT PDF
                </a>
                <a href="{{ route('reports.export', 'excel') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-black hover:bg-slate-50 transition-all">
                    <i class="fas fa-file-excel mr-1 text-emerald-500"></i> EXPORT EXCEL
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-8 py-5">Receipt ID</th>
                        <th class="px-8 py-5">Time</th>
                        <th class="px-8 py-5">Cashier</th>
                        <th class="px-8 py-5">Method</th>
                        <th class="px-8 py-5 text-right">Amount</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <!-- Real-Time Transaction List -->
                    <template x-for="sale in stats.sales" :key="sale.id">
                        <tr class="hover:bg-slate-50 transition-all group animate-fade-in">
                            <td class="px-8 py-4 font-black text-slate-700 text-sm" x-text="sale.receipt_number"></td>
                            <td class="px-8 py-4 text-slate-500 font-medium text-xs" x-text="formatTime(sale.created_at)"></td>
                            <td class="px-8 py-4 text-slate-600 font-bold text-sm" x-text="sale.cashier.name"></td>
                            <td class="px-8 py-4">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full border border-indigo-100 uppercase" x-text="sale.payment_method"></span>
                            </td>
                            <td class="px-8 py-4 text-right font-black text-slate-900" x-text="'₱' + parseFloat(sale.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2})"></td>
                            <td class="px-8 py-4 text-right">
                                <button @click="viewReceipt(sale)" class="w-10 h-10 rounded-xl bg-slate-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center border border-slate-100">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </template>

                    <!-- Empty State -->
                    <template x-if="stats.sales.length === 0">
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fas fa-inbox text-6xl mb-4"></i>
                                    <p class="font-black uppercase tracking-widest">No Transactions Found</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function realtimeReports(range) {
    return {
        range: range,
        stats: {
            sales: @json($sales),
            totalRevenue: {{ $totalRevenue }},
            estimatedProfit: {{ $estimatedProfit }},
            transactionCount: {{ $transactionCount }},
            inventoryValueCost: {{ $inventoryValueCost }},
            inventoryValueRetail: {{ $inventoryValueRetail }},
            lowStockProducts: @json($lowStockProducts),
            lastUpdated: '{{ $lastUpdated }}'
        },
        selectedSale: null,
        showReceiptModal: false,

        formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        },
        formatFullDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        viewReceipt(sale) {
            this.selectedSale = sale;
            this.showReceiptModal = true;
        },
        chart: null,
        init() {
            this.initChart(@json($chartLabels), @json($chartValues));

            // Poll for new stats every 10 seconds
            setInterval(() => {
                fetch(`{{ route('reports.stats') }}?range=${range}`)
                    .then(response => response.json())
                    .then(data => {
                        this.stats = data;
                        this.updateChart(data.chartLabels, data.chartValues);
                    })
                    .catch(err => console.error('Real-time sync failed:', err));
            }, 10000);
        },
        initChart(labels, values) {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: values,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { weight: '600' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: '600' } }
                        }
                    }
                }
            });
        },
        updateChart(labels, values) {
            if (this.chart) {
                this.chart.data.labels = labels;
                this.chart.data.datasets[0].data = values;
                this.chart.update('none');
            }
        }
    }
}
</script>
@endsection
