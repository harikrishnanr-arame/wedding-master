@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
    <!--
        Registration page template for user account creation.
        This view provides a form for new users to register with name, phone, email, and password,
        and displays an image section on the right.
    -->
<link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">

<div class="page">

  <!-- LEFT FORM -->
  <div class="form-section">
    <h1>Create an Account</h1>
    <p class="subtitle">Sign up to get started!</p>

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/name.jpg') }}" alt="Name Icon">
            <input type="text" name="name" placeholder="Full Name" required>
        </div>
        
        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/call.jpg') }}" alt="Phone Icon">
            <input type="tel" name="phone" placeholder="Phone Number">
        </div>

        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/mail-icon.png') }}" alt="Email Icon">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/lock.png') }}" alt="Password Icon">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/lock.png') }}" alt="Confirm Password Icon">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="signup-btn">SIGN UP</button>

    </form>

    <p class="login-link">
      Already have an account? <a href="{{ route('login') }}">Log In</a>
    </p>
  </div>

  <!-- RIGHT IMAGE -->
  <div class="image-section">
    <div class="main-image">
      <img src="{{ asset('assets/img/login_signup/couple2.jpg') }}" alt="Couple">
    </div>

    <div class="floating-card">
      <img src="{{ asset('assets/img/login_signup/couple.jpg') }}" alt="Couple Card">
    </div>
  </div>

</div>
@endsection
