@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Laporan Penggunaan Bahan Baku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item">Data Bahan Baku</li>
                <li class="breadcrumb-item active">Laporan Penggunaan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Laporan</h5>
                        
                        <form action="{{ route('raw-materials.report') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Bahan Baku</label>
                                <select name="material_id" class="form-select">
                                    <option value="">Semua Bahan</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>
                                            {{ $material->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                                <a href="{{ route('raw-materials.report') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hasil Laporan</h5>

                        @if($logs->isEmpty())
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    <h4 class="alert-heading">Belum Ada Data Laporan</h4>
                                    <p class="mb-0">
                                        Belum ada aktivitas pergerakan stok bahan baku yang tercatat. Beberapa hal yang bisa Anda lakukan:
                                    </p>
                                    <ul class="mt-2 mb-0">
                                        <li>Pastikan Anda telah menambahkan data bahan baku</li>
                                        <li>Lakukan pencatatan stok masuk untuk bahan baku yang tersedia</li>
                                        <li>Catat penggunaan bahan baku saat digunakan dalam produksi</li>
                                        <li>Periksa rentang tanggal filter jika Anda menggunakannya</li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Waktu Transaksi</th>
                                            <th class="text-center">Pengguna</th>
                                            <th class="text-center">Detail Transaksi</th>
                                            <th class="text-center">Total Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedLogs = $logs->groupBy(function($log) {
                                                return $log->created_at->format('Y-m-d H:i:s');
                                            });
                                        @endphp

                                        @foreach($groupedLogs as $timestamp => $transactions)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($timestamp)->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td>
                                                    @php
                                                        $user = $transactions->first()->user;
                                                    @endphp
                                                    @if($user)
                                                        @if($user->roles === 'admin')
                                                            <span class="badge bg-primary">Admin</span>
                                                            {{ $user->admin->nama ?? '-' }}
                                                        @else
                                                            <span class="badge bg-info">Karyawan</span>
                                                            {{ $user->karyawan->nama ?? '-' }}
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Bahan Baku</th>
                                                                <th>Tipe</th>
                                                                <th>Jumlah</th>
                                                                <th>Harga Satuan</th>
                                                                <th>Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($transactions as $log)
                                                                <tr>
                                                                    <td>
                                                                        {{ $log->rawMaterial->name }}
                                                                        <br>
                                                                        <small class="text-muted">{{ $log->notes ?: '-' }}</small>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($log->type === 'in')
                                                                            <span class="badge bg-success">Stok Masuk</span>
                                                                        @elseif($log->type === 'out')
                                                                            <span class="badge bg-warning">Stok Keluar</span>
                                                                        @elseif($log->type === 'adjustment')
                                                                            <span class="badge bg-info">Penambahan bahan baku baru</span>
                                                                        @elseif($log->type === 'production')
                                                                            <span class="badge bg-primary">Produksi</span>
                                                                        @elseif($log->type === 'expired')
                                                                            <span class="badge bg-danger">Kadaluarsa</span>
                                                                        @elseif($log->type === 'damaged')
                                                                            <span class="badge bg-dark">Rusak</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-end">
                                                                        {{ number_format($log->quantity, 2) }} {{ $log->rawMaterial->unit }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        Rp {{ number_format($log->price, 0, ',', '.') }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        Rp {{ number_format($log->subtotal, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="text-end">
                                                    <strong>
                                                        Rp {{ number_format($transactions->sum('subtotal'), 0, ',', '.') }}
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        @if(!$logs->isEmpty())
            if (!$.fn.DataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "pageLength": 25,
                    "ordering": true,
                    "order": [[1, 'desc']],
                    "columnDefs": [
                        {
                            "targets": 0,
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        }
                    ],
                    "language": {
                        "sEmptyTable": "Tidak ada data yang tersedia",
                        "sInfo": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                        "sInfoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
                        "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "Tampilkan _MENU_ entri",
                        "sLoadingRecords": "Memuat...",
                        "sProcessing": "Memproses...",
                        "sSearch": "Cari:",
                        "sZeroRecords": "Tidak ditemukan data yang sesuai",
                        "oPaginate": {
                            "sFirst": "Pertama",
                            "sLast": "Terakhir",
                            "sNext": "Selanjutnya",
                            "sPrevious": "Sebelumnya"
                        }
                    }
                });
            }
        @endif
    });
</script>
@endpush 