@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Data Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Manajemen</li>
            <li class="breadcrumb-item active">Data Transaksi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transaksi</h5>
                    <div class="d-flex justify-content-start">
                        <a href="{{ route('transaksi.create') }}" class="btn btn-primary m-1">Tambah Transaksi</a>
                        <a href="{{ route('transaksi.export-excel') }}" class="btn btn-success m-1">
                            <i class="bi bi-file-earmark-text"></i> Export CSV
                        </a>
                    </div>
                    <table class="table datatable" id="myTable">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Tanggal Transaksi</th>
                                <th class="text-center">Total Harga (Rp)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $transaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                                    <td class="text-center">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('transaksidetail.show', $transaksi->id) }}" class="btn btn-info m-1" title="Lihat Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning m-1">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $transaksi->id }}">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </div>
                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="deleteModal{{ $transaksi->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $transaksi->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $transaksi->id }}">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus transaksi tanggal <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form method="POST" action="{{ route('transaksi.destroy', $transaksi->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
