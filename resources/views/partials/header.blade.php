<!-- Navbar -->
<header class="hero-nav">
    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#templates">Templates</a></li>

            @if(auth()->check())
                <li><a href="{{ url('/profile') }}">Profile</a></li>
            @else
                <li><a href="{{ route('register') }}">Sign Up</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
            @endif

        </ul>
    </nav>
</header>
