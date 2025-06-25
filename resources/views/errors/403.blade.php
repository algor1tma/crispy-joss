@extends('auth.main')

@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>403</h1>
            <h3 class="fs-4 fw-bold" style="color: #012970;">Akses Tidak Diizinkan.</h3>
            <p class="text-center">Maaf, akses Anda untuk melakukan tindakan ini tidak diizinkan.<br>
            Mohon hubungi administrator untuk bantuan lebih lanjut</p>
            <a class="btn" href="javascript:history.back()">Kembali</a>
            <img src="assets/img/unauthorized.svg" width="200px" class="img-fluid py-5" alt="Page Not Found">
        </section>

    </div>
@endsection
