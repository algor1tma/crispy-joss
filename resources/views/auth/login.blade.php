@extends('auth.main')

@section('content')
    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <div class="text-center">
                                    <img src="{{ asset('img/iconcriospyy.png') }}" alt="Logo"
                                        class="img-fluid rounded-circle mb-3" style="width: 80px; height: 80px;">
                                    {{-- <h2 class="h4 text-gray-900 mb-4" style="color: black">Selamat Datang</h2> --}}
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4" style="color: black">Login</h5>
                                        <p class="text-center small">masukkan username dan password untuk login</p>
                                    </div>

                                    <form action="{{ route('authenticateAuth') }}" method="POST"
                                        class="row g-3 needs-validation">
                                        @csrf

                                        <div class="col-12">
                                            <label for="username" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" id="username" name="username"
                                                    value="{{ old('username') }}" required minlength="3" maxlength="50"
                                                    pattern="[a-zA-Z0-9_]+" title="Username hanya boleh berisi huruf, angka, dan underscore"
                                                    placeholder="Masukkan username">
                                                <div class="invalid-feedback">Silakan masukkan username Anda</div>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password" name="password"
                                                    required minlength="6" placeholder="Masukkan password">
                                                <button type="button" class="btn-show-password input-group-text"
                                                    onclick="togglePasswordVisibility()">
                                                    <i id="password-toggle-icon" class="bi bi-eye-slash"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">Silakan masukkan password Anda</div>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>
    @if (session('loginError'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('loginError') }}'
            });
        </script>
    @endif

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var passwordButton = document.querySelector(".btn-show-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordButton.innerHTML = '<i class="bi bi-eye"></i>';
            } else {
                passwordInput.type = "password";
                passwordButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
            }
        }

        // Form validation
        $(document).ready(function() {
            // Username validation
            $('#username').on('input', function() {
                const username = $(this).val();

                if (username.length > 0 && username.length < 3) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Username minimal 3 karakter');
                } else if (username.length > 0 && !/^[a-zA-Z0-9_]+$/.test(username)) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Username hanya boleh berisi huruf, angka, dan underscore');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Password validation
            $('#password').on('input', function() {
                const password = $(this).val();

                if (password.length > 0 && password.length < 6) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Password minimal 6 karakter');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Form submission validation
            $('form').on('submit', function(e) {
                const username = $('#username').val().trim();
                const password = $('#password').val().trim();

                let hasError = false;

                if (!username || username.length < 3) {
                    $('#username').addClass('is-invalid');
                    $('#username').siblings('.invalid-feedback').text('Username minimal 3 karakter');
                    hasError = true;
                }

                if (!password || password.length < 6) {
                    $('#password').addClass('is-invalid');
                    $('#password').siblings('.invalid-feedback').text('Password minimal 6 karakter');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
