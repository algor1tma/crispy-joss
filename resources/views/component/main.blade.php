<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Crispy Joss</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/produk/iconcriospyy.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/produk/iconcriospyy.png') }}">

    @include('component.head')
</head>

<body>
    @include('component.header')
    @include('component.sidebar')

    <main id="main" class="main">
        @yield('content')
    </main>

    @include('component.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short">
        </i>
    </a>
    @include('component.js')
    @include('sweetalert::alert')

</body>

</html>
