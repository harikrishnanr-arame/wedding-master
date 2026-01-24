@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <!--
        Login page template for user authentication.
        This view provides a form for users to log in with email and password,
        displays error messages, and offers social login options.
    -->
<link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">

<div class="login-wrapper">
  <div class="login-card">

    <img src="{{ asset('assets/img/floraldesign.png') }}" class="floral" alt="floral" />

    <h1>Welcome Back!</h1>
    <p class="subtitle">Please log in to your account.</p>

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
      <div style="color:red; margin-bottom:10px;">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="input-group">
        <img src="{{ asset('assets/img/login_signup/mail-icon.png') }}" />
        <input
          type="email"
          name="email"
          placeholder="Email Address"
          value="{{ old('email') }}"
          required
        />
      </div>

      <div class="input-group">
        <img src="{{ asset('assets/img/login_signup/lock.png') }}" />
        <input
          type="password"
          name="password"
          placeholder="Password"
          required
        />
      </div>

      <div class="forgot">
        <a href="#">Forgot password?</a>
      </div>

      <button type="submit" class="login-btn">Log In</button>
    </form>

    <p class="register">
      Don’t have an account?
      <a href="{{ route('register') }}">Register</a>
    </p>

    <div class="divider">
      <span>or log with</span>
    </div>

    <div class="social-buttons">
      <button class="social google" type="button">
        <img src="{{ asset('assets/img/login_signup/google.png') }}" />
        Google
      </button>

      <button class="social facebook" type="button">
        <img src="{{ asset('assets/img/login_signup/fb.png') }}" alt="Facebook" />
        Facebook
      </button>
    </div>

  </div>
</div>
@endsection
