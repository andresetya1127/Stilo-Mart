@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Total Transaksi</p>
                <p class="text-3xl font-bold">{{ $totalTransactions }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Pendapatan Hari Ini</p>
                <p class="text-2xl font-bold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-bold">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-sm p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <select name="payment_method"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Kartu</option>
                        <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                        Filter
                    </button>
                    {{-- <a href="{{ route('transactions.export', request()->query()) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Export CSV
                    </a> --}}
                    <a href="{{ route('transactions.pdf', request()->query()) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Print PDF
                    </a>
                    <a href="{{ route('transactions.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bayar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-semibold text-indigo-600">{{ $transaction->invoice_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transaction->items->count() }} item
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">Rp
                                        {{ number_format($transaction->total, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    Rp {{ number_format($transaction->paid, 0, ',', '.') }}
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('transactions.show', $transaction) }}"
                                            class="text-indigo-600 hover:text-indigo-900" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('transactions.print', $transaction) }}" target="_blank"
                                            class="text-green-600 hover:text-green-900" title="Cetak">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if($transaction->status !== 'completed')
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
