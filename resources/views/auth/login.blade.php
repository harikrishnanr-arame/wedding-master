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
          id="email"
          placeholder="Email Address"
          value="{{ old('email') }}"
          required
        />
      </div>
      <small id="emailError" style="color:red;"></small>


      <div class="input-group">
        <img src="{{ asset('assets/img/login_signup/lock.png') }}" />
        <input
          type="password"
          name="password"
          placeholder="Password"
          id="password"
          required
        />
        <span class="eye" onclick="togglePassword('password')">👁</span>
      </div>
      <small id="passwordError" style="color:red;"></small>

      <div class="forgot">
        <a href="{{ route('password.request') }}">Forgot password?</a>
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
      <button class="social google" type="button" onclick="window.location='{{ route('google.login') }}'">
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

<script>
// Password toggle
function togglePassword(id) {
    const field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}

//Validation
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    form.addEventListener("submit", function (e) {

        let isValid = true;

        emailError.textContent = "";
        passwordError.textContent = "";

        //Email validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email.value.trim())) {
            emailError.textContent = "Please enter a valid email address.";
            isValid = false;
        }

        //Password validation
        //Minimum 6 characters
        if (password.value.length < 6) {
            passwordError.textContent = "Password must be at least 6 characters.";
            isValid = false;
        }

        //If invalid
        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>

@endsection
