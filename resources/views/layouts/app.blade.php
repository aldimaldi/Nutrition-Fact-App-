<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>NutriQuest — @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Memanggil CSS eksternal yang sudah dipisah -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="shell">
        <!-- Memanggil komponen sidebar -->
        @include('layouts.sidebar')

        <main class="main">
            <!-- Konten halaman yang berubah-ubah akan masuk ke sini -->
            @yield('content')
        </main>
    </div>

    <!-- Tempat untuk menyisipkan Script dari masing-masing halaman -->
    @stack('scripts')
</body>
</html>