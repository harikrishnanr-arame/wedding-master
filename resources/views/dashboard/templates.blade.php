@extends('layouts.dashboard')

@section('title', 'Templates')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/userDashboard_templates.css') }}">
@endpush

@section('content')
    <h2 class="welcome">Welcome Back Aswin TK</h2>

    <div class="section-title">
      Choose Your Template
    </div>

    <div class="templates-grid">
      <div class="template-card">
        <img src="./img/i.jpg" alt="">
        <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
      </div>

      <div class="template-card">
        <img src="./img/i1.jpg" alt="">
        <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
      </div>

      <div class="template-card">
        <img src="./img/i2.png" alt="">
        <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
      </div>

      <div class="template-card">
        <img src="./img/i3.png" alt="">
        <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
      </div>

      <div class="template-card">
        <img src="./img/i4.png" alt="">
        <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
      </div>

      <div class="template-card">
        <img src="./img/i5.png" alt="">
        <button onclick="location.href='templateEditPage.html'">Choose This Template</button>
      </div>
    </div>
  </main>
</div>
@endsection
