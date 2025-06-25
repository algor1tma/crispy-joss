@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Perbarui Karyawan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item"><a href="{{ route('indexKaryawan') }}">Data Karyawan</a></li>
                <li class="breadcrumb-item active">Perbarui Karyawan</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-start align-items-center">
                <h5 class="card-title">Karyawan</h5>
            </div>

            <!-- Form untuk Memperbarui Data Karyawan -->
            <form action="{{ route('updateKaryawan', $karyawan->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')  <!-- Menambahkan metode PUT di sini -->

                <!-- Foto Karyawan -->
                <div class="col-12 text-center mb-3">
                    @if ($karyawan && $karyawan->foto)
                        <img src="{{ asset('img/DataKaryawan/' . $karyawan->foto) }}" alt="Profile" class="profile-photo-lg rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('img/default-profile.png') }}" alt="Profile" class="profile-photo-lg rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                </div>

                <!-- Nama Karyawan -->
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $karyawan->nama) }}" maxlength="20" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $karyawan->user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="col-md-6">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $karyawan->alamat) }}">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- No Telp -->
                <div class="col-md-6">
                    <label for="no_telp" class="form-label">No Telp <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp', $karyawan->no_telp) }}" oninput="limitNumberLength(this, 14)" required>
                    @error('no_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol Aksi -->
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('indexKaryawan') }}" class="btn btn-danger m-1">Batal</a>
                        <button type="submit" class="btn btn-primary m-1">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Fungsi untuk membatasi panjang nomor telepon
    function limitNumberLength(element, maxLength) {
        if (element.value.length > maxLength) {
            element.value = element.value.slice(0, maxLength);
        }
    }
</script>
@endpush
