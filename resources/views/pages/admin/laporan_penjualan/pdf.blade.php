<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .page-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .page-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .meta-info {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .summary-table {
            width: 100%;
            margin-top: 20px;
        }
        .summary-table td {
            padding: 5px;
            border: none;
        }
        .summary-title {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer-text {
            margin-top: 60px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="company-name">LAPORAN PENJUALAN</div>
        <div class="page-title">PERIODE: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
        <div class="meta-info">Dicetak pada: {{ $tanggal_cetak }}</div>
    </div>

    <h3>Ringkasan</h3>
    <table class="summary-table" border="0">
        <tr>
            <td class="summary-title" width="200">Total Pendapatan</td>
            <td>: Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="summary-title">Jumlah Transaksi</td>
            <td>: {{ $summary['jumlah_transaksi'] }}</td>
        </tr>
        <tr>
            <td class="summary-title">Total Produk Terjual</td>
            <td>: {{ $summary['produk_terjual'] }} unit</td>
        </tr>
    </table>

    <h3>Detail Laporan Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="100">Tanggal</th>
                <th>Nama Produk</th>
                <th width="80">Jumlah</th>
                <th width="150">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($laporan as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td class="text-center">{{ $item->total_terjual }}</td>
                <td class="text-right">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data laporan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">TOTAL</th>
                <th class="text-center">{{ $summary['produk_terjual'] }}</th>
                <th class="text-right">Rp {{ number_format($summary['total_penjualan'], 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>Mengetahui,</div>
        <div class="footer-text">________________</div>
        <div>Manager</div>
    </div>
</body>
</html> 