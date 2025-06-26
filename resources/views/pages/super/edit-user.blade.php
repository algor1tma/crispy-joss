@extends('component.main')

@section('content')
    <div class="pagetitle mb-4">
        <h1>Edit User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('indexDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super.manage-users') }}">Manage Users</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit User Information</h5>

                        <form action="{{ route('super.update-user', $user->id) }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')

                            <!-- Account Information -->
                            {{-- <div class="col-12">
                                <h6 class="fw-bold text-primary border-bottom pb-2">Account Information</h6>
                            </div> --}}
                            {{-- <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required
                                    placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan alamat email yang valid</div>
                            </div> --}}

                            {{-- <!-- Personal Information -->
                            <div class="col-12 mt-4">
                                <h6 class="fw-bold text-primary border-bottom pb-2">Personal Information</h6>
                            </div> --}}

                            @php
                                $profile = $user->admin ?? $user->karyawan;
                            @endphp

                            <div class="col-md-6">
                                <label for="nama" class="form-label">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama', $profile->nama ?? '') }}" required
                                    pattern="[a-zA-Z\s]+" title="Nama hanya boleh berisi huruf dan spasi"
                                    placeholder="Masukkan nama lengkap">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Hanya boleh berisi huruf dan spasi</div>
                            </div>

                            <div class="col-md-6">
                                <label for="no_telp" class="form-label">Phone Number <span
                                        class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('no_telp') is-invalid @enderror"
                                    id="no_telp" name="no_telp" value="{{ old('no_telp', $profile->no_telp ?? '') }}"
                                    required maxlength="14" pattern="[0-9]{8,14}" title="Nomor HP harus 8-14 digit angka"
                                    placeholder="Masukkan nomor HP (8-14 digit)">
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan 8-14 digit angka</div>
                            </div>

                            <div class="col-12">
                                <label for="alamat" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                    required>{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" minlength="6"
                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password (minimal 6 karakter jika
                                    diisi)</div>
                            </div>

                            <div class="col-md-6">
                                <label for="roles" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('roles') is-invalid @enderror" id="roles"
                                    name="roles" required>
                                    <option value="">Choose Role</option>
                                    <option value="admin" {{ old('roles', $user->roles) === 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="karyawan"
                                        {{ old('roles', $user->roles) === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Update User
                                    </button>
                                    <a href="{{ route('super.manage-users') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Phone number validation and formatting
            $('#no_telp').on('input', function() {
                let value = $(this).val();

                // Remove non-numeric characters
                value = value.replace(/\D/g, '');

                // Limit to 14 digits
                if (value.length > 14) {
                    value = value.substring(0, 14);
                }

                $(this).val(value);
                $(this).removeClass('is-invalid');

                // Real-time validation feedback
                if (value.length > 0 && (value.length < 8 || value.length > 14)) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Nomor HP harus 8-14 digit angka');
                } else if (value.length >= 8 && value.length <= 14) {
                    $(this).removeClass('is-invalid');
                }
            });

            // Form validation before submit
            $('form').on('submit', function(e) {
                const phoneValue = $('#no_telp').val().trim();

                if (phoneValue && !/^[0-9]{8,14}$/.test(phoneValue)) {
                    e.preventDefault();
                    $('#no_telp').addClass('is-invalid');
                    $('#no_telp').siblings('.invalid-feedback').text('Nomor HP harus 8-14 digit angka');
                    return false;
                }
            });

            // Remove validation errors on input
            $('.form-control').on('input', function() {
                if ($(this).attr('id') !== 'no_telp') {
                    $(this).removeClass('is-invalid');
                }
            });

            // Enhanced password validation
            $('#password').on('input', function() {
                const password = $(this).val();

                if (password.length > 0 && password.length < 6) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Password minimal 6 karakter');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Email validation
            $('#email').on('input', function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email.length > 0 && !emailRegex.test(email)) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Format email tidak valid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Name validation
            $('#nama').on('input', function() {
                const name = $(this).val();
                const nameRegex = /^[a-zA-Z\s]+$/;

                if (name.length > 0 && !nameRegex.test(name)) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Nama hanya boleh berisi huruf dan spasi');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
