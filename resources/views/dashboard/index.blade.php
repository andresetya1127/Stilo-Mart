@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Products -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 transform transition-all hover:scale-105 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Services -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 transform transition-all hover:scale-105 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Jasa</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalServices }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today Transactions -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 transform transition-all hover:scale-105 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Transaksi Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $todayTransactions }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today Revenue -->
            <div
                class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 transform transition-all hover:scale-105 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pendapatan Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart & Low Stock -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendapatan 6 Bulan Terakhir</h3>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Low Stock Alert -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Stok Menipis</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex-1">
                                <p class="font-medium text-gray-800 text-sm">{{ $product->name }}</p>
                                <p class="text-xs text-gray-600">SKU: {{ $product->sku }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-red-600">{{ $product->stock }} {{ $product->unit }}</p>
                                <p class="text-xs text-gray-500">Min: {{ $product->min_stock }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm">Semua stok aman</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
              @if (auth()->user()->isAdmin())
                    <a href="{{ route('transactions.index') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Lihat Semua →
                </a>
              @endif
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $transaction->invoice_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $transaction->user->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $transaction->items->count() }} item</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                    @if ($transaction->payment_method === 'cash') bg-green-100 text-green-800
                                    @elseif($transaction->payment_method === 'card') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($transaction->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p>Belum ada transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js for Revenue Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        const monthlyData = @json($monthlyRevenue);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: monthlyData.map(item => item.revenue),
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
