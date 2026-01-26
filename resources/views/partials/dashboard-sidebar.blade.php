<aside class="sidebar">

  <div class="profile">
    <h4>{{ auth()->user()->name }}</h4>
    <p>{{ auth()->user()->email }}</p>
  </div>

  <ul class="menu">
    <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
    </li>

    <li class="{{ request()->is('dashboard/templates') ? 'active' : '' }}">
      <a href="{{ route('dashboard.templates') }}">📄 Templates</a>
    </li>

    <li class="{{ request()->is('dashboard/payments') ? 'active' : '' }}">
      <a href="{{ route('dashboard.payments') }}">💳 Payments</a>
    </li>
  </ul>

</aside>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Dashboard')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- COMMON CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/userDashboar.css') }}">

  <!-- PAGE SPECIFIC CSS -->
  @stack('styles')
</head>
<body>

<button class="menu-toggle">☰</button>

<div class="layout">

  {{-- COMMON SIDEBAR --}}
  @include('dashboard.sidebar')

  <!-- PAGE CONTENT -->
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
