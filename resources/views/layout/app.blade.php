<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div class="wrapper">
        @include('layout.navbar')
        @include('layout.sidebar')

        <div class="content-wrapper">
            @yield('content')
        </div>

        @include('layout.footer')
    </div>
</body>
</html>