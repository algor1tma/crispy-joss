@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Karyawan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item active">Data Karyawan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Karyawan</h5>
                        <div class="d-flex justify-content-start mb-3">
                            <a href="{{ route('createKaryawan') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Tambah Karyawan
                            </a>
                        </div>
                        <table class="table datatable" id="myTable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>No Telp</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($karyawans as $karyawan)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            @if ($karyawan->foto)
                                                <img src="{{ asset('img/DataKaryawan/' . $karyawan->foto) }}" alt="Foto Karyawan" class="profile-photo">
                                            @else
                                                <img src="{{ asset('img/default-profile.png') }}" alt="Default Profile" class="profile-photo">
                                            @endif
                                        </td>
                                        <td>{{ $karyawan->nama }}</td>
                                        <td>{{ $karyawan->user->email }}</td>
                                        <td>{{ $karyawan->alamat ?? '-' }}</td>
                                        <td>{{ $karyawan->no_telp ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('editKaryawan', $karyawan->id) }}" 
                                                   class="btn btn-warning btn-sm"
                                                   title="Edit Karyawan">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $karyawan->user_id }}"
                                                        title="Hapus Karyawan">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="deleteModal{{ $karyawan->user_id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus karyawan <strong>{{ $karyawan->nama }}</strong>?</p>
                                                    <p class="text-danger mb-0"><small>Semua data terkait karyawan ini akan ikut terhapus.</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form method="POST" action="{{ route('deleteKaryawan', $karyawan->user_id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
