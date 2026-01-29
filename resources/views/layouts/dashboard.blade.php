<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- COMMON CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/userDashboar.css') }}">

  <!-- PAGE CSS -->
  @stack('styles')
</head>
<body>

<button class="menu-toggle">☰</button>

<div class="layout">

  {{-- COMMON SIDEBAR --}}
  @include('dashboard.sidebar')

  <main class="content">
    @yield('content')
  </main>

</div>

<script>
  const toggleBtn = document.querySelector('.menu-toggle');
  const sidebar = document.querySelector('.sidebar');

  toggleBtn.addEventListener('click', e => {
    e.stopPropagation();
    sidebar.classList.toggle('active');
  });

  document.addEventListener('click', () => {
    if (window.innerWidth <= 768) {
      sidebar.classList.remove('active');
    }
  });
</script>

</body>
</html>
