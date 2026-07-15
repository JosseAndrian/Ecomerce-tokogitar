<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts  = Product::count();
        $totalCategories = Category::count();
        $totalOrders    = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalIncome    = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('total_price');

        // Pending orders waiting for action
        $pendingOrders  = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Monthly revenue for chart (last 12 months)
        $monthlyRevenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Build 12-month labels & data arrays
        $chartLabels = [];
        $chartData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->format('M Y');
            $chartLabels[] = $label;
            $match = $monthlyRevenue->first(fn($r) =>
                $r->year == $date->year && $r->month == $date->month
            );
            $chartData[] = $match ? (float) $match->total : 0;
        }

        // Order status breakdown
        $orderStatusData = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')->get()
            ->keyBy('status');

        // Top 5 best-selling products
        $topProducts = OrderDetail::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $recentChats = User::whereHas('messages')
            ->withCount(['messages as unread_count' => function($query) {
                $query->where('is_from_admin', false)->where('is_read', false);
            }])
            ->with(['messages' => function($query) {
                $query->latest();
            }])
            ->get()
            ->sortByDesc(function($user) {
                return $user->messages->max('created_at');
            })
            ->take(5);

        return view('admin.dashboard', compact(
            'totalProducts', 'totalCategories', 'totalOrders',
            'totalCustomers', 'totalIncome', 'pendingOrders', 'processingOrders',
            'recentOrders', 'recentChats',
            'chartLabels', 'chartData', 'orderStatusData', 'topProducts'
        ));
    }
}

