<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 8px;
            margin: 0;
            padding: 2mm;
            width: 54mm;
            color: #000;
            background: #fff;
            line-height: 1.2;
        }

        .receipt {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        h4 {
            font-size: 10px;
            margin: 1mm 0;
            font-weight: bold;
        }

        p {
            margin: 0.5mm 0;
            font-size: 7px;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 1mm 0;
            width: 100%;
        }

        .item-row {
            margin: 0.5mm 0;
            font-size: 7px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 0.2mm;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8mm;
        }

        .total-section {
            margin-top: 1mm;
            padding-top: 1mm;
            border-top: 1px dashed #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 8px;
        }

        .footer {
            margin-top: 2mm;
            text-align: center;
            font-size: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 0;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="text-center">
            <h4>CRISPY JOSS</h4>
            <p>Jl. Raya No. 123</p>
            <p>Telp: 0831-2993-0489</p>
        </div>

        <div class="separator"></div>

        <div class="text-center">
            <p><strong>STRUK PEMBELIAN</strong></p>
            <p>{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            <p>No: #{{ $transaction->id }}</p>
        </div>

        <div class="separator"></div>

        <!-- Items -->
        @foreach($transaction->details as $detail)
        <div class="item-row">
            <div class="item-name">{{ $detail->produk->nama_produk }}</div>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 20%;">{{ $detail->quantity }} x</td>
                    <td style="width: 40%; text-align: right;">{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td style="width: 40%; text-align: right;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        @endforeach

        <!-- Total -->
        <div class="total-section">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%; font-weight: bold;">TOTAL:</td>
                    <td style="width: 40%; text-align: right; font-weight: bold;">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="separator"></div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih telah berbelanja</p>
            <p>di Crispy Joss</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
        </div>
    </div>
</body>
</html>
