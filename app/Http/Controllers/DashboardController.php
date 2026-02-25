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
            ->where('payment_method', 'cash')
            ->limit(10)
            ->get();

             // Debit transactions
        $debitTransactions = Transaction::with('user', 'items')
            ->latest()
            ->where('payment_method', 'debit')
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

        // Top selling products (this month)
        $topSellingProducts = \DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transaction_items.item_type', 'product')
            ->where('transactions.status', 'completed')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->select('transaction_items.item_name', \DB::raw('SUM(transaction_items.quantity) as total_sold'))
            ->groupBy('transaction_items.item_id', 'transaction_items.item_name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Top selling services (this month)
        $topSellingServices = \DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transaction_items.item_type', 'service')
            ->where('transactions.status', 'completed')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->select('transaction_items.item_name', \DB::raw('SUM(transaction_items.quantity) as total_sold'))
            ->groupBy('transaction_items.item_id', 'transaction_items.item_name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalServices',
            'todayTransactions',
            'todayRevenue',
            'monthRevenue',
            'lowStockProducts',
            'recentTransactions',
            'debitTransactions',
            'monthlyRevenue',
            'topSellingProducts',
            'topSellingServices'
        ));
    }
}
