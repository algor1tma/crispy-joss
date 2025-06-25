@extends('component.auth')

@section('title', 'Registrasi Karyawan')

@section('content')
                <div class="container">
                    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                                <div class="card-body">
                    <div class="p-4">
                        <div class="text-center mb-4">
                            <h4 class="text-primary">Registrasi Akun Karyawan</h4>
                            <p>Silakan isi data karyawan dengan lengkap</p>
                                    </div>

                                    <form action="{{ route('storeAuth') }}" method="POST">
                                        @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                    name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                        </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
        
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                    name="no_telp" value="{{ old('no_telp') }}" required>
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                        </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                    name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                        </div>
    
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                        </div>
    
                            <div class="mb-4">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                    name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                    </div>
    
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Daftar Karyawan</button>
                                <a href="{{ route('indexDashboard') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection