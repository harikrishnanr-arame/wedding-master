<!DOCTYPE html>
<!--
    Main application layout template.
    This layout provides the base HTML structure for all pages, including meta tags,
    CSS links for home, template, review, and footer styles, and includes the header partial.
    Content is yielded from child views.
-->
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