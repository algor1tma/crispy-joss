@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Bahan Baku Stok Menipis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item">Data Bahan Baku</li>
                <li class="breadcrumb-item active">Stok Menipis</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Daftar Bahan Baku Stok Menipis</h5>

                        @if($materials->isEmpty())
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div>
                                    <h4 class="alert-heading">Stok Bahan Baku Aman</h4>
                                    <p class="mb-0">
                                        Saat ini tidak ada bahan baku yang memiliki stok di bawah batas minimum. Beberapa tips untuk menjaga stok tetap aman:
                                    </p>
                                    <ul class="mt-2 mb-0">
                                        <li>Pantau penggunaan bahan baku secara berkala</li>
                                        <li>Atur minimum stok sesuai dengan kebutuhan produksi</li>
                                        <li>Lakukan pemesanan ulang sebelum stok benar-benar menipis</li>
                                        <li>Evaluasi penggunaan bahan baku melalui laporan</li>
                                    </ul>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Bahan</th>
                                            <th class="text-center">Stok Saat Ini</th>
                                            <th class="text-center">Minimum Stok</th>
                                            <th class="text-center">Satuan</th>
                                            {{-- <th class="text-center">Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($materials as $material)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $material->name }}</td>
                                                <td class="text-center text-danger fw-bold">{{ number_format($material->stock) }}</td>
                                                <td class="text-center">{{ number_format($material->minimum_stock) }}</td>
                                                <td class="text-center">{{ $material->unit }}</td>
                                                {{-- <td class="text-center">
                                                    <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#adjustStock{{ $material->id }}">
                                                        <i class="bi bi-arrow-left-right"></i> Sesuaikan Stok
                                                    </button>

                                                    <!-- Modal Adjust Stock -->
                                                    <div class="modal fade" id="adjustStock{{ $material->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('raw-materials.adjust-stock', $material) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Penyesuaian Stok - {{ $material->name }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Tipe</label>
                                                                            <select name="type" class="form-select" required>
                                                                                <option value="in">Stok Masuk</option>
                                                                                <option value="out">Stok Keluar</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Jumlah ({{ $material->unit }})</label>
                                                                            <input type="number" name="quantity" class="form-control" required min="1">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Catatan</label>
                                                                            <textarea name="notes" class="form-control" rows="2"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td> --}}
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
        @if(!$materials->isEmpty())
            if (!$.fn.DataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "pageLength": 25,
                    "ordering": true,
                    "order": [[1, 'asc']],
                    "columnDefs": [
                        {
                            "targets": 0,
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            "targets": 5,
                            "orderable": false,
                            "searchable": false
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