<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'VenResto QR Menu')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Global QR Customer CSS --}}
    <link href="{{ asset('assets/css/qr/customer-layout.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

    {{-- TOPBAR --}}
    {{-- <nav class="qr-navbar">
        <div class="container">
            <a class="qr-brand" href="#">
                <span class="qr-brand-icon">
                    <i class="bi bi-shop"></i>
                </span>
                <span>VenResto</span>
            </a>
        </div>
    </nav> --}}

    {{-- CONTENT --}}
    <main class="qr-main">
        @yield('content')
    </main>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Global QR Customer JS --}}
    <script src="{{ asset('assets/js/qr/customer-order.js') }}"></script>

    @stack('scripts')
</body>
</html>