@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Produk</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item active">Data Produk</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Produk</h5>
                        <div class="d-flex justify-content-start">
                            @if(auth()->user()->roles === 'admin')
                            <a href="{{ route('produk.create') }}" class="btn btn-primary m-1">Tambah Produk</a>
                            @endif
                        </div>
                        <table class="table datatable" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Foto</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Deskripsi</th>
                                    @if(auth()->user()->roles === 'admin')
                                    <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $produk)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            @if ($produk->foto)
                                                <img src="{{ asset('img/produk/' . $produk->foto) }}" alt="Foto Produk" style="max-width: 60px;">
                                            @else
                                                Tidak ada foto
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $produk->nama_produk }}</td>
                                        <td class="text-center">Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $produk->stok_produk }}</td>
                                        <td class="text-center">{{ $produk->deskripsi_produk ?? '-' }}</td>
                                        @if(auth()->user()->roles === 'admin')
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning m-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $produk->id }}">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </div>
                                            <!-- Modal Delete -->
                                            <div class="modal fade" id="deleteModal{{ $produk->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $produk->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $produk->id }}">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus produk <strong>{{ $produk->nama_produk }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <form method="POST" action="{{ route('produk.destroy', $produk->id) }}">
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
                                        @endif
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
