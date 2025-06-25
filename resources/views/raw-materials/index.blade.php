@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Bahan Baku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item active">Data Bahan Baku</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bahan Baku</h5>
                        <div class="d-flex justify-content-start">
                            @if(auth()->user()->roles === 'admin')
                            <a href="{{ route('raw-materials.create') }}" class="btn btn-primary m-1">Tambah Bahan Baku</a>
                            @endif
                        </div>

                        <table class="table datatable" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Bahan</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Minimum Stok</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Harga/Satuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($materials as $material)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $material->name }}</td>
                                        <td class="text-center">{{ number_format($material->stock) }}</td>
                                        <td class="text-center">{{ number_format($material->minimum_stock) }}</td>
                                        <td class="text-center">{{ $material->unit }}</td>
                                        <td class="text-center">Rp {{ number_format($material->price) }}</td>
                                        <td class="text-center">
                                            @if($material->stock <= $material->minimum_stock)
                                                <span class="badge bg-danger">Stok Menipis</span>
                                            @else
                                                <span class="badge bg-success">Stok Aman</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#adjustStock{{ $material->id }}">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </button>
                                                @if(auth()->user()->roles === 'admin')
                                                <a href="{{ route('raw-materials.edit', $material) }}" class="btn btn-warning m-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $material->id }}">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                                @endif
                                            </div>

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

                                            <!-- Modal Delete -->
                                            @if(auth()->user()->roles === 'admin')
                                            <div class="modal fade" id="deleteModal{{ $material->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus bahan <strong>{{ $material->name }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('raw-materials.destroy', $material) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
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