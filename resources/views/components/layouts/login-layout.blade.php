<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - SIPRESKAR KSM 10</title>
    <meta name="description" content="Sistem Presensi Karang Taruna KSM 10" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS khusus untuk auth -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">

    <!-- Helpers & Config -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    @livewireStyles
</head>

<body>
    <!-- Content -->
    {{ $slot }}
    <!-- / Content -->

    <!-- Core JS DITAMBAHKAN data-navigate-once -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}" data-navigate-once></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}" data-navigate-once></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}" data-navigate-once></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}" data-navigate-once></script>

    <!-- Github Button -->
    <script async defer src="https://buttons.github.io/buttons.js" data-navigate-once></script>

    @livewireScripts
</body>
</html>