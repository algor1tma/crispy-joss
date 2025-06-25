@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Karyawan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item"><a href="{{ route('indexKaryawan') }}">Data Karyawan</a></li>
                <li class="breadcrumb-item active">Tambah Karyawan</li>
            </ol>
        </nav>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-start align-items-center">
                <h5 class="card-title">Karyawan</h5>
            </div>

            <!-- Form untuk menambah karyawan -->
            <form action="{{ route('storeKaryawan') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <!-- Seksi Foto Karyawan -->
                <div class="col-12">
                    <label for="imageKaryawan" class="form-label">Foto Karyawan</label><br>
                    <input class="form-control @error('imageKaryawan') is-invalid @enderror" type="file" name="imageKaryawan" id="imageKaryawan" accept="image/*">
                    @error('imageKaryawan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="imagePreview" class="mt-2">
                        <img src="{{ asset('img/default-profile.png') }}" alt="Profile" class="profile-photo-lg rounded-circle" style="width: 150px; height: 150px;">
                    </div>
                </div>

                <!-- Seksi Nama Karyawan -->
                <div class="col-12">
                    <label for="nama" class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" maxlength="20" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seksi Alamat (tampil untuk admin dan karyawan) -->
                <div class="col-12">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <!-- Seksi Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                <!-- Seksi No Telp -->
                <div class="col-md-6">
                    <label for="no_telp" class="form-label">No Telp <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" oninput="limitNumberLength(this, 14)" required>
                    @error('no_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Seksi Password (password default 'karyawan123') -->
                <div class="col-md-6">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" value="karyawan123" required>
                        <button type="button" class="btn btn-outline-secondary btn-show-password" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                

                <!-- Tombol Aksi: Simpan dan Batal -->
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
$(document).ready(function() {
    // Preview gambar saat input file berubah
    $("#imageKaryawan").change(function() {
        readURL(this, "#imagePreview");
    });

    // Fungsi untuk menampilkan preview gambar
    function readURL(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    $(previewId).html('<img src="' + e.target.result + '" class="profile-photo-lg">');
                };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});

// Fungsi untuk menampilkan atau menyembunyikan password
function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var icon = document.querySelector(".btn-show-password i");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    } else {
        passwordInput.type = "password";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    }
}

// Batasi panjang nomor telepon maksimal 14 digit
function limitNumberLength(element, maxLength) {
    if (element.value.length > maxLength) {
        element.value = element.value.slice(0, maxLength);
    }
}
</script>
@endpush
