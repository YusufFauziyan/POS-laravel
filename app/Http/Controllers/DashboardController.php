<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\OrderStatus;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Today's sales
        $todaySales = Order::whereDate('created_at', today())
            ->where('status', OrderStatus::COMPLETED)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(grand_total), 0) as total')
            ->first();

        // This month's sales
        $monthSales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', OrderStatus::COMPLETED)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(grand_total), 0) as total')
            ->first();

        // Total products
        $totalProducts = Product::count();

        // Low stock products (stock < 10)
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // Recent orders (last 10)
        $recentOrders = Order::with('user')
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Best selling products (top 5)
        $bestSelling = OrderItem::select('product_name')
            ->selectRaw('SUM(quantity) as total_sold, SUM(subtotal) as revenue')
            ->groupBy('product_name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // Daily sales for chart (last 7 days)
        $dailySales = Order::where('status', OrderStatus::COMPLETED)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COALESCE(SUM(grand_total), 0) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates with 0
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[] = [
                'date' => $date,
                'total' => $dailySales->get($date)?->total ?? 0,
            ];
        }

        // Payment method distribution
        $paymentStats = Order::where('status', OrderStatus::COMPLETED)
            ->selectRaw('payment_method, COUNT(*) as count, COALESCE(SUM(grand_total), 0) as total')
            ->groupBy('payment_method')
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'today_sales' => [
                    'count' => $todaySales->count ?? 0,
                    'total' => $todaySales->total ?? 0,
                ],
                'month_sales' => [
                    'count' => $monthSales->count ?? 0,
                    'total' => $monthSales->total ?? 0,
                ],
                'total_products' => $totalProducts,
                'low_stock_count' => $lowStockCount,
            ],
            'recent_orders' => $recentOrders,
            'best_selling' => $bestSelling,
            'chart_data' => $chartData,
            'payment_stats' => $paymentStats,
        ]);
    }
}
