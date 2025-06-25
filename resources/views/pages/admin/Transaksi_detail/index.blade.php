@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Data Detail Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item active">Detail Transaksi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail Transaksi</h5>
            <a href="{{ route('transaksidetail.create') }}" class="btn btn-primary mb-3">Tambah Detail Transaksi</a>
            <table class="table datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->produk->nama_produk }}</td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('transaksidetail.edit', $detail->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('transaksidetail.destroy', $detail->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus data?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
