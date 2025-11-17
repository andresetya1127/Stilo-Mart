@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invoice #{{ $transaction->invoice_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Kasir: {{ $transaction->user->name }}</p>
                    <p class="text-sm text-gray-500">Metode Pembayaran: <span class="font-medium">{{ ucfirst($transaction->payment_method) }}</span></p>
                </div>
            </div>

            <div class="border-t pt-6">
                <h2 class="text-lg font-semibold mb-4">Item Transaksi</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->item->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border-t pt-6 mt-6">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold">Total: </span>
                    <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-lg font-semibold">Dibayar: </span>
                    <span class="text-xl font-bold text-green-600">Rp {{ number_format($transaction->paid, 0, ',', '.') }}</span>
                </div>
                @if($transaction->total > $transaction->paid)
                    <div class="flex justify-between items-center mt-2 text-red-600">
                        <span class="text-lg font-semibold">Kekurangan: </span>
                        <span class="text-xl font-bold">Rp {{ number_format($transaction->total - $transaction->paid, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('transactions.print', $transaction) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Cetak
            </a>
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Kembali
            </a>
        </div>
    </div>
@endsection
