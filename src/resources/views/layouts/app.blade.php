<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <title>@yield('title', '')</title>

    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    @stack('styles')
    @yield('css')
</head>
<body>
    <main class="main">
        @yield('content')
    </main>
</body>
</html>