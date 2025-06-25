@extends('component.main')

@section('content')
    @if (auth()->user()->roles === 'admin')
        <div class="pagetitle mb-4">
            <h1>Dashboard Admin</h1>
            <p class="text-secondary mb-0">Selamat datang <b>{{ $myData->nama }}</b></p>
        </div>
        <section class="section dashboard">
            <div class="row g-4">
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row g-4">
                        <!-- Daily Transactions Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Transaksi <span class="text-secondary fw-normal">| Hari ini</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary-light">
                                            <i class="bi bi-cart text-primary"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">{{ $dailyTransactions ?? 0 }}</h2>
                                            <span class="text-muted small">transaksi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Revenue Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Pendapatan <span class="text-secondary fw-normal">| Bulan ini</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success-light">
                                            <i class="bi bi-coin text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">Rp{{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h2>
                                            <span class="text-muted small">rupiah</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Expenses Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Pengeluaran <span class="text-secondary fw-normal">| Bulan ini</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger-light">
                                            <i class="bi bi-cash text-danger"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">Rp{{ number_format($monthlyExpenses ?? 0, 0, ',', '.') }}</h2>
                                            <span class="text-muted small">rupiah</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Stats Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Pengguna Aktif</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info-light">
                                            <i class="bi bi-people text-info"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">{{ $userStats['active'] ?? 0 }}</h2>
                                            <div class="mt-2">
                                                <span class="badge bg-primary me-2">Admin: {{ $userStats['admin'] ?? 0 }}</span>
                                                <span class="badge bg-secondary">Kasir: {{ $userStats['kasir'] ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recipe Status Card -->
                        <div class="col-xxl-6 col-xl-12">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Status Resep Produk</h5>
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 rounded bg-light">
                                                <h2 class="mb-2">{{ $productRecipeStats['total_products'] ?? 0 }}</h2>
                                                <p class="mb-0 text-muted">Total Produk</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 rounded bg-success-light">
                                                <h2 class="mb-2">{{ $productRecipeStats['with_recipe'] ?? 0 }}</h2>
                                                <p class="mb-0 text-success">Resep Lengkap</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 rounded bg-warning-light">
                                                <h2 class="mb-2">{{ $productRecipeStats['without_recipe'] ?? 0 }}</h2>
                                                <p class="mb-0 text-warning">Belum Ada Resep</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Materials Card -->
                        <div class="col-xxl-6 col-xl-12">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Bahan Baku Menipis</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Bahan</th>
                                                    <th class="text-center">Stok</th>
                                                    <th class="text-center">Minimum</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($lowStockMaterials as $material)
                                                    <tr>
                                                        <td class="fw-medium">{{ $material->name }}</td>
                                                        <td class="text-center">{{ $material->current_stock }} {{ $material->unit }}</td>
                                                        <td class="text-center">{{ $material->minimum_stock }} {{ $material->unit }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-danger">Stok Rendah</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">
                                                            <i class="bi bi-info-circle me-2"></i>Tidak ada bahan baku yang menipis
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products with Low Materials Card -->
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Produk dengan Bahan Baku Menipis</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Bahan Menipis</th>
                                                    <th class="text-center">Stok Bahan</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($productsWithLowMaterials as $product)
                                                    <tr>
                                                        <td class="fw-medium">{{ $product->nama_produk }}</td>
                                                        <td>{{ $product->material_name }}</td>
                                                        <td class="text-center">{{ $product->current_stock }} / {{ $product->minimum_stock }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning">Perlu Perhatian</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">
                                                            <i class="bi bi-info-circle me-2"></i>Tidak ada produk dengan bahan baku menipis
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue Chart -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Pendapatan <span class="text-secondary fw-normal">| 30 Hari Terakhir</span></h5>
                                    <div id="revenueChart" class="chart-container"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        @push('scripts')
        <script>
            var options = {
                series: [{
                    name: 'Pendapatan',
                    data: @json($revenueData['amounts'] ?? [])
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    borderColor: '#f1f1f1',
                    row: {
                        colors: ['transparent'],
                        opacity: 0.5
                    },
                    padding: {
                        top: 10,
                        right: 10,
                        bottom: 10,
                        left: 10
                    }
                },
                xaxis: {
                    categories: @json($revenueData['dates'] ?? []),
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            fontSize: '12px',
                            fontFamily: 'inherit'
                        }
                    },
                    axisBorder: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        },
                        style: {
                            fontSize: '12px',
                            fontFamily: 'inherit'
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                },
                colors: ['#4154f1']
            };

            var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();
        </script>

        <style>
            .bg-primary-light { background-color: rgba(65, 84, 241, 0.1); }
            .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
            .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
            .bg-info-light { background-color: rgba(13, 202, 240, 0.1); }
            .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }

            .card-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }

            .info-card h2 {
                font-size: 28px;
                font-weight: 700;
            }

            .chart-container {
                min-height: 400px;
            }

            .table > :not(caption) > * > * {
                padding: 0.75rem 1rem;
            }

            .badge {
                padding: 0.5em 0.8em;
                font-weight: 500;
            }
        </style>
        @endpush
    @elseif (auth()->user()->roles === 'karyawan')
        <div class="pagetitle mb-4">
            <h1>Dashboard Karyawan</h1>
            <p class="text-secondary mb-0">Selamat datang <b>{{ $myData->nama }}</b></p>
        </div>
        <section class="section dashboard">
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row align-items-center">
                        <!-- Daily Transactions Card -->
                        <div class="col-xxl-6 col-md-8">
                            <div class="card info-card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Transaksi <span class="text-secondary fw-normal">| Hari ini</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary-light">
                                            <i class="bi bi-cart text-primary"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">{{ $dailyTransactions ?? 0 }}</h2>
                                            <span class="text-muted small">transaksi</span>
                                        </div>
                                        <div class="ms-auto">
                                            <img src="{{ asset('img/iconcriospyy.png') }}" alt="Icon"  style="width: 80; height: 80px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Resep produk -->
                        <div class="col-xxl-6 col-xl-12">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Status Resep Produk</h5>
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 rounded bg-light">
                                                <h2 class="mb-2">{{ $productRecipeStats['total_products'] ?? 0 }}</h2>
                                                <p class="mb-0 text-muted">Total Produk</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 rounded bg-success-light">
                                                <h2 class="mb-2">{{ $productRecipeStats['with_recipe'] ?? 0 }}</h2>
                                                <p class="mb-0 text-success">Resep Lengkap</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 rounded bg-warning-light">
                                                <h2 class="mb-2">{{ $productRecipeStats['without_recipe'] ?? 0 }}</h2>
                                                <p class="mb-0 text-warning">Belum Ada Resep</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Materials Card -->
                        <div class="col-xxl-6 col-xl-12">
                            <div class="card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Bahan Baku Menipis</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Bahan</th>
                                                    <th class="text-center">Stok</th>
                                                    <th class="text-center">Minimum</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($lowStockMaterials as $material)
                                                    <tr>
                                                        <td class="fw-medium">{{ $material->name }}</td>
                                                        <td class="text-center">{{ $material->current_stock }} {{ $material->unit }}</td>
                                                        <td class="text-center">{{ $material->minimum_stock }} {{ $material->unit }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-danger">Stok Rendah</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">
                                                            <i class="bi bi-info-circle me-2"></i>Tidak ada bahan baku yang menipis
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products with Low Materials Card -->
                        <div class="col-xxl-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-4">Produk dengan Bahan Baku Menipis</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Bahan Menipis</th>
                                                    <th class="text-center">Stok Bahan</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($productsWithLowMaterials as $product)
                                                    <tr>
                                                        <td class="fw-medium">{{ $product->nama_produk }}</td>
                                                        <td>{{ $product->material_name }}</td>
                                                        <td class="text-center">{{ $product->current_stock }} / {{ $product->minimum_stock }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning">Perlu Perhatian</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-4 text-muted">
                                                            <i class="bi bi-info-circle me-2"></i>Tidak ada produk dengan bahan baku menipis
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
    @elseif (auth()->user()->roles === 'pelanggan')
        <div class="pagetitle mb-4">
            <h1>Dashboard</h1>
            <p class="text-secondary mb-0">Selamat datang <b>{{ $myData->nama }}</b></p>
        </div>
        <section class="section dashboard">
            <div class="row g-4">
                <div class="col-12">
                    <div class="row g-4">
                        <!-- Points Card -->
                        <div class="col-md-6">
                            <div class="card info-card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Poin Anda</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success-light">
                                            <i class="bi bi-coin text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">{{ $myData->poin }}</h2>
                                            <span class="text-muted small">poin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reservations Card -->
                        <div class="col-md-6">
                            <div class="card info-card h-100">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-3">Reservasi <span class="text-secondary fw-normal">| Mendatang</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary-light">
                                            <i class="bi bi-calendar-check text-primary"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h2 class="mb-0">{{ $countReservasi ?? 0 }}</h2>
                                            <span class="text-muted small">reservasi</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
