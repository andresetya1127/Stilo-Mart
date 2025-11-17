<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .items-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .items-list li {
            margin-bottom: 2px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi StiloMart</h1>
        <p>{{ now()->format('d F Y') }}</p>
        @if(request('start_date') && request('end_date'))
            <p>Periode: {{ request('start_date') }} s/d {{ request('end_date') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Items</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Metode</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>
                        <ul class="items-list">
                            @foreach($transaction->items as $item)
                                <li>{{ $item->item->name }} ({{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($transaction->payment_method) }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ now()->format('d F Y H:i:s') }}</p>
        <p>StiloMart - Sistem Manajemen Toko</p>
    </div>
</body>
</html>
