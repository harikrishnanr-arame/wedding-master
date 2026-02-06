<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title')</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin_users.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin_settings.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin_content.css') }}">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
/* KEEP ALL YOUR COMMON CSS HERE */
*{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }
body{ background:#eef2f7; }
.dashboard{ display:flex; height:100vh; }
.sidebar{ width:250px; background:#1f2a44; color:white; padding:20px 0; }
.sidebar h2{ text-align:center; margin-bottom:30px; }
.sidebar ul{ list-style:none; }
.sidebar ul li{ padding:15px 25px; }
.sidebar ul li a{
    color:white;
    text-decoration:none;
    display:block;
}
.sidebar ul li:hover,
.sidebar ul li.active{
    background:#2e3c5c;
    border-left:4px solid #4e8cff;
}
.main{ flex:1; padding:25px; overflow:auto; }
.topbar{ display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
.profile{ background:#4e8cff; padding:8px 15px; border-radius:30px; color:white; }
.panel{ background:white; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.05); }
</style>

</head>
<body>

<div class="dashboard">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin</h2>
        <ul>
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>

            <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <a href="{{ route('admin.users') }}">Users</a>
            </li>

            <li class="{{ request()->routeIs('admin.content') ? 'active' : '' }}">
                <a href="{{ route('admin.content') }}">Manage Content</a>
            </li>

            <li class="{{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                <a href="{{ route('admin.payments') }}">Payments</a>
            </li>

            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.settings') }}">Settings</a>
            </li>
        </ul>

        <div class="sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>

    </div>
    
    <!-- Main Content -->
    <div class="main">

        <div class="topbar">
            <h2>@yield('page-title')</h2>
            <div class="profile">Admin ▼</div>
        </div>

        @yield('content')

    </div>

</div>

@yield('scripts')

</body>
</html>
