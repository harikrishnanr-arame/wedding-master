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

    <form method="POST" action="{{ url('/register') }}" id="registerForm">
        @csrf

        {{-- Name --}}
        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/name.jpg') }}" alt="Name Icon">
            <input 
                type="text" 
                name="name" 
                id="name"
                placeholder="Full Name"
                value="{{ old('name') }}"
                required
                pattern="^[A-Za-z ]+$"
                title="Name should contain only letters"
            >
        </div>
        @error('name')
            <small class="error">{{ $message }}</small>
        @enderror

        {{-- Phone --}}
        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/call.jpg') }}" alt="Phone Icon">
            <input 
                type="tel" 
                name="phone"
                id="phone"
                placeholder="Phone Number"
                value="{{ old('phone') }}"
                pattern="[0-9]{10,15}"
                maxlength="15"
                inputmode="numeric"
            >
        </div>
        @error('phone')
            <small class="error">{{ $message }}</small>
        @enderror

        {{-- Email --}}
        <div class="input-group">
            <img src="{{ asset('assets/img/login_signup/mail-icon.png') }}" alt="Email Icon">
            <input 
                type="email" 
                name="email"
                id="email"
                placeholder="Email Address"
                value="{{ old('email') }}"
                required
            >
        </div>
        @error('email')
            <small class="error">{{ $message }}</small>
        @enderror

        {{-- Password --}}
        <div class="input-group password-wrapper">
            <img src="{{ asset('assets/img/login_signup/lock.png') }}" alt="Password Icon">
            <input 
                type="password" 
                name="password"
                id="password"
                placeholder="Password"
                required
                minlength="6"
            >
            <span class="eye" onclick="togglePassword('password')">👁</span>
        </div>
        @error('password')
            <small class="error">{{ $message }}</small>
        @enderror

        {{-- Confirm Password --}}
        <div class="input-group password-wrapper">
            <img src="{{ asset('assets/img/login_signup/lock.png') }}" alt="Confirm Password Icon">
            <input 
                type="password" 
                name="password_confirmation"
                id="confirmPassword"
                placeholder="Confirm Password"
                required
                minlength="6"
            >
            <span class="eye" onclick="togglePassword('confirmPassword')">👁</span>
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

<script>
document.getElementById("registerForm").addEventListener("submit", function(e) {

    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    let errors = [];

    // Name validation
    if (!/^[A-Za-z ]+$/.test(name)) {
        errors.push("Full name must contain only letters.");
    }

    // Phone validation
    if (phone && !/^[0-9]{10}$/.test(phone)) {
        errors.push("Phone number must contain 10 digits only.");
    }

    // Email validation
    if (!/^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/.test(email)) {
        errors.push("Please enter a valid email address.");
    }

    // Password match
    if (password !== confirmPassword) {
        errors.push("Passwords do not match.");
    }

    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join("\n"));
    }
});

// Restrict phone typing
document.getElementById("phone").addEventListener("input", function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Password toggle
function togglePassword(id) {
    const field = document.getElementById(id);
    field.type = field.type === "password" ? "text" : "password";
}
</script>

@endsection
