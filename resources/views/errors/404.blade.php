@extends('auth.main')

@section('content')
    <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>404</h1>
            <h2>Halaman yang Anda cari tidak ditemukan.</h2>
            <a class="btn" href="javascript:history.back()">Kembali</a>
            <img src="assets/img/not-found.svg" width="250px" class="img-fluid py-5" alt="Page Not Found">
        </section>

    </div>
@endsection
