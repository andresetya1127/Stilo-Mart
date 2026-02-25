<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode - {{ $product->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            text-align: center;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .barcode-container {
            margin: 20px 0;
        }

        .barcode-image {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }

        .product-name {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }

        .barcode-text {
            font-size: 12px;
            letter-spacing: 2px;
            margin: 5px 0;
        }

        .no-print {
            margin-top: 30px;
        }

        .no-print button {
            padding: 10px 20px;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .no-print button:hover {
            background: #3730a3;
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
    <div class="barcode-container">
        <div class="product-name">{{ $product->name }}</div>
        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" class="barcode-image">
        <div class="barcode-text">{{ $product->barcode }}</div>
    </div>

    <div class="no-print">
        <button onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Barcode
        </button>
        <button onclick="window.close()" style="margin-left: 10px; background: #6B7280;">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>
