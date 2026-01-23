<aside class="sidebar">
  <div class="profile">
    <img src="{{ asset('assets/img/userdash/untitled (7).jpeg') }}" alt="User">
    <h4>{{ auth()->user()->user_name }}</h4>
    <p>{{ auth()->user()->email }}</p>
    <p>{{ auth()->user()->mobile_number }}</p>
  </div>

  <ul class="menu">
    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a style="text-decoration: none; color: black;" href="{{ route('dashboard') }}">🏠 Dashboard</a>
    </li>

    <li class="{{ request()->routeIs('dashboard.templates') ? 'active' : '' }}">
      <a style="text-decoration: none; color: black;" href="{{ route('dashboard.templates') }}">📄 Templates</a>
    </li>

    <li class="{{ request()->routeIs('dashboard.payments') ? 'active' : '' }}">
      <a style="text-decoration: none; color: black;" href="{{ route('dashboard.payments') }}">💳 Payments</a>
    </li>
  </ul>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="logout">⏻ Log Out</button>
  </form>
</aside>
