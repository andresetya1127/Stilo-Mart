<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalProducts = Product::count();
        $totalServices = Service::count();
        $todayTransactions = Transaction::today()->completed()->count();
        $todayRevenue = Transaction::today()->completed()->sum('total');
        $monthRevenue = Transaction::thisMonth()->completed()->sum('total');

        // Low stock products
        $lowStockProducts = Product::lowStock()->active()->limit(5)->get();

        // Recent transactions
        $recentTransactions = Transaction::with('user', 'items')
            ->latest()
            ->limit(10)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Transaction::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->completed()
                ->sum('total');

            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        return view('dashboard.index', compact(
            'totalProducts',
            'totalServices',
            'todayTransactions',
            'todayRevenue',
            'monthRevenue',
            'lowStockProducts',
            'recentTransactions',
            'monthlyRevenue'
        ));
    }
}
