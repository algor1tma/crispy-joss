@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Detail Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail Transaksi - {{ $tanggal }}</h5>

                    <!-- Informasi Transaksi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 200px">Kode Transaksi</th>
                                    <td>: {{ $transaksi->kode_transaksi }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal & Waktu</th>
                                    <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <td>: {{ $transaksi->customer_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>: {{ $transaksi->customer_phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Detail Produk -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->produk->nama_produk }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total transaksi:</th>
                                    <th class="text-end">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection 