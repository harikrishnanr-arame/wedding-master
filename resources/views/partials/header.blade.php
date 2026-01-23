<!-- Navbar -->
<header class="hero-nav">
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="{{ url('/#templates') }}">Templates</a></li>

            @if(auth()->check())
                <li><a href="{{ url('dashboard') }}">Profile</a></li>
            @else
                <li><a href="{{ route('register') }}">Sign Up</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
            @endif

        </ul>
    </nav>
</header>
