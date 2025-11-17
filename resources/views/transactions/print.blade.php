<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            margin: 2px 0;
        }

        .info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .items {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .item {
            margin: 8px 0;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .summary {
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            border-bottom: 2px solid #000;
            padding: 8px 0;
            margin-top: 10px;
        }

        .payment {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 20px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>STILOMART</h1>
        <p>Sistem Kasir Modern</p>
        <p>Jl. Contoh No. 123, Kota</p>
        <p>Telp: (021) 1234-5678</p>
    </div>

    <div class="info">
        <div class="info-row">
            <span>Invoice:</span>
            <strong>{{ $transaction->invoice_number }}</strong>
        </div>
        <div class="info-row">
            <span>Tanggal:</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Kasir:</span>
            <span>{{ $transaction->user->name }}</span>
        </div>
        <div class="info-row">
            <span>Metode:</span>
            <span>{{ ucfirst($transaction->payment_method) }}</span>
        </div>
    </div>

    <div class="items">
        @foreach ($transaction->items as $item)
            <div class="item">
                <div class="item-name">{{ $item->item->name }}</div>
                <div class="item-detail">
                    <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                </div>
            </div>
        @endforeach
    </div>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>

        @if ($transaction->tax > 0)
            <div class="summary-row">
                <span>Pajak:</span>
                <span>Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
            </div>
        @endif

        @if ($transaction->discount > 0)
            <div class="summary-row">
                <span>Diskon:</span>
                <span>- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="summary-row total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payment">
        <div class="summary-row">
            <span>Bayar:</span>
            <span>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Kembalian:</span>
            <strong>Rp {{ number_format($transaction->change, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        <p style="margin-top: 10px;">{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #4F46E5; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            Cetak Struk
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; background: #6B7280; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            Tutup
        </button>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            // Uncomment to auto print
            // window.print();
        }
    </script>
</body>

</html>
