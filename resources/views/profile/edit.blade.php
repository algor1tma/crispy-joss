@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Edit Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('indexDashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Edit Profile</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-start align-items-center">
                <h5 class="card-title">Profile</h5>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                {{-- Section for Karyawan (Employee) --}}
                @if(auth()->user()->roles === 'karyawan')
                <div class="col-12">
                    <label for="foto" class="form-label">Foto Profile</label><br>
                    <input class="form-control @error('foto') is-invalid @enderror" type="file" name="foto" id="foto" accept="image/*">
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="imagePreview" class="mt-2">
                        @if(auth()->user()->karyawan && auth()->user()->karyawan->foto)
                            <img src="{{ asset('img/DataKaryawan/' . auth()->user()->karyawan->foto) }}" 
                                alt="Profile" class="profile-photo-lg">
                        @else
                            <img src="{{ asset('img/default-profile.png') }}" 
                                alt="Profile" class="profile-photo-lg">
                        @endif
                    </div>
                </div>

                
                {{-- Section for Admin --}}
                {{-- @if(auth()->user()->roles === 'admin')
                <div class="col-12">
                    <label for="foto" class="form-label">Foto Profile</label><br>
                    <input class="form-control @error('foto') is-invalid @enderror" type="file" name="foto" id="foto" accept="image/*">
                    @error('foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="imagePreview" class="mt-2">
                        @if(auth()->user()->admins && auth()->user()->admins->foto)
                            <img src="{{ asset('img/DataAdmin/' . auth()->user()->admins->foto) }}" 
                                alt="Profile" class="profile-photo-lg">
                        @else
                            <img src="{{ asset('img/default-profile.png') }}" 
                                alt="Profile" class="profile-photo-lg">
                        @endif
                    </div>
                </div>
                @endif --}}

                {{-- Common Fields (both Admin and Karyawan) --}}
                <div class="col-6">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                        id="nama" name="nama" maxlength="20" required
                        value="{{ auth()->user()->roles === 'admin' ? (auth()->user()->admins->nama ?? '') : (auth()->user()->karyawans->nama ?? '') }}">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control @error('alamat') is-invalid @enderror" 
                        id="alamat" name="alamat" value="{{ auth()->user()->karyawans->alamat ?? '' }}">
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif


                <div class="col-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                        id="email" name="email" required value="{{ auth()->user()->email }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Change Section (for both Admin and Karyawan) --}}
                <div class="col-12">
                    <label for="password" class="form-label">Password Baru (opsional)</label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password">
                        <button type="button" class="btn btn-outline-secondary btn-show-password" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        <button type="button" class="btn btn-outline-secondary btn-show-password" onclick="toggleConfirmPasswordVisibility()">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('indexDashboard') }}" class="btn btn-danger m-1">Batal</a>
                        <button type="submit" class="btn btn-primary m-1">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview
    $("#foto").change(function() {
        readURL(this, "#imagePreview");
    });

    // Function to read and display image preview
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

// Toggle password visibility
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

function toggleConfirmPasswordVisibility() {
    var passwordInput = document.getElementById("password_confirmation");
    var icon = document.querySelector("#password_confirmation + .btn-show-password i");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    } else {
        passwordInput.type = "password";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    }
}

// Input validation for name field
document.getElementById('nama').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Hanya huruf dan spasi
});
</script>
@endpush
