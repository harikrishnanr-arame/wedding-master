<aside class="sidebar">
  <div class="profile">
    <img src="{{ asset('assets/img/userdash/user_img.jpeg') }}" alt="User">
    <h4>{{ auth()->user()->user_name }}</h4>
    <p>{{ auth()->user()->email }}</p>
    <p>{{ auth()->user()->mobile_number }}</p>
  </div>

  <ul class="menu">

  {{-- Home --}}
  <li>
    <a href="{{ route('home') }}">
      🏡 Home
    </a>
  </li>

  {{-- Dashboard --}}
  <li class="{{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
    <a href="{{ route('dashboard.profile') }}">
      🏠 Dashboard
    </a>
  </li>

  {{-- Templates --}}
  <li class="{{ request()->routeIs('dashboard.templates') ? 'active' : '' }}">
    <a href="{{ route('dashboard.templates') }}">
      📄 Templates
    </a>
  </li>

  {{-- Currently Editing --}}
  @if(isset($latestTemplate))
      <li class="{{ request()->routeIs('template.edit') ? 'active' : '' }}">
          <a href="{{ route('template.edit', $latestTemplate->id) }}">
              ✏️ Editing: {{ $latestTemplate->title }}
          </a>
      </li>
  @endif

  {{-- Payments --}}
  <li class="{{ request()->routeIs('dashboard.payments') ? 'active' : '' }}">
    <a href="{{ route('dashboard.payments') }}">
      💳 Payments
    </a>
  </li>

</ul>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button class="logout">⏻ Log Out</button>
  </form>
</aside>
