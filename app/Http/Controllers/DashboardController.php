<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $topSellingProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();
        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'top_selling_products' => $topSellingProducts,
            'recent_orders' => $recentOrders,
        ]);
    }
}
