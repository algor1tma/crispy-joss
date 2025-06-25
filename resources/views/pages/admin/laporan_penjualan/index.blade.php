@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Laporan Penjualan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item active">Laporan Penjualan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <!-- Filter Form Card -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Filter Laporan</h5>
            <form action="{{ route('laporanpenjualan.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $startDate) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $endDate) }}">
                </div>
                <div class="col-md-3">
                    <label for="view_mode" class="form-label">Tampilan Grafik</label>
                    <select class="form-select" id="view_mode" name="view_mode">
                        <option value="daily" {{ $viewMode == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="monthly" {{ $viewMode == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporanpenjualan.index') }}" class="btn btn-secondary">Reset</a>
                    <a href="{{ route('laporanpenjualan.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                        class="btn btn-danger" target="_blank">
                        <i class="bi bi-file-pdf"></i> Ekspor PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Card -->
    <div class="row">
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6>Rp {{ number_format($summary['total_penjualan'] ?? 0, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Transaksi</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $summary['jumlah_transaksi'] ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Transaksi -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail Transaksi</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu Transaksi</th>
                        @if($hasCustomerColumns)
                        <th>Customer</th>
                        @endif
                        <th>Detail Produk</th>
                        <th>Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $currentTransactionTime = '';
                        $groupedTransactions = $laporan->groupBy('tanggal_transaksi');
                    @endphp

                    @forelse ($groupedTransactions as $tanggal => $transactions)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y H:i') }}</td>
                            @if($hasCustomerColumns)
                            <td>
                                <div>{{ $transactions->first()->customer_name }}</div>
                                <small class="text-muted">{{ $transactions->first()->customer_phone }}</small>
                            </td>
                            @endif
                            <td>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless mb-0">
                                        @foreach ($transactions as $item)
                                        <tr>
                                            <td style="width: 50%">{{ $item->nama_produk }}</td>
                                            <td style="width: 20%">× {{ $item->total_terjual }}</td>
                                            <td style="width: 30%" class="text-end">Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($transactions->sum('total_penjualan'), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $hasCustomerColumns ? 5 : 4 }}" class="text-center">Tidak ada data transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $laporan->firstItem() }} to {{ $laporan->lastItem() }} of {{ $laporan->total() }} results
                    </div>
                    <div>
                        <!-- Pagination buttons with custom size -->
                        <div class="pagination-wrapper">
                            {{ $laporan->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Parse data from PHP to JS
        const labels = @json($chartLabels);
        const values = @json($chartValues);

        // Initialize chart
        new ApexCharts(document.querySelector("#salesChart"), {
            series: [{
                name: 'Total Penjualan',
                data: values
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: true
                }
            },
            markers: {
                size: 4
            },
            colors: ['#4154f1'],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                type: 'category',
                categories: labels
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        }).render();
    });
</script>
@endpush

@section('style')
<style>
.pagination-wrapper {
    display: flex;
    justify-content: flex-end;
    font-size: 14px; /* Ukuran teks lebih kecil */
}

.pagination-wrapper .page-item {
    margin: 0 5px;
}

.pagination-wrapper .page-link {
    padding: 5px 10px; /* Mengurangi padding untuk tombol yang lebih kecil */
    font-size: 14px; /* Mengatur ukuran font tombol */
    border-radius: 4px; /* Sudut tombol lebih halus */
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #4154f1;
    border-color: #4154f1;
    color: white;
}

.pagination-wrapper .page-link:hover {
    background-color: #4154f1;
    border-color: #4154f1;
    color: white;
}
</style>
@endsection
@endsection
