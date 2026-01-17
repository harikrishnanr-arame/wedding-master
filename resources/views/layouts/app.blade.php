<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/review.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.header')
    @yield('content')

    @stack('scripts')
</body>
</html>