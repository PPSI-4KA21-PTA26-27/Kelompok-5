<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Jakarta');

        $stats = [
            'orders_today'    => Order::whereDate('created_at', $today)->count(),
            'pending_payment' => Order::where('payment_status', 'unpaid')->count(),
            'to_pack'         => Order::where('order_status', 'dikemas')->count(),
            'revenue_month'   => Order::where('payment_status', 'paid')
                                      ->whereMonth('created_at', $today->month)
                                      ->whereYear('created_at', $today->year)
                                      ->sum('total_amount'),
            'total_products'  => Product::count(),
            'low_stock_count' => ProductVariant::where('stock', '<=', 5)->count(),
        ];

        $recentOrders = Order::with('items')
            ->latest()
            ->take(5)
            ->get();

        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockVariants'));
    }
}