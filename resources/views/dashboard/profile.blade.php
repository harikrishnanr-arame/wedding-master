@extends('layouts.dashboard')

@section('title', 'User Dashboard')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/userDashboar.css') }}">
@endpush  

@section('content')

    <h2 class="welcome">Welcome Back, Aswin 👋</h2>

    <!-- QUICK ACTIONS -->
    <div class="section-title">Quick Actions</div>

    <div class="dashboard-grid">

      <a href="templates.html"
        style="display:block; text-decoration:none; color:inherit; cursor:pointer;"
        class="dash-card">
        <h3>➕ Create Website</h3>
        <p>Choose a wedding template and start editing</p>
      </a>

      <a href="templateEditPage.html"
        style="display:block; text-decoration:none; color:inherit; cursor:pointer;"
        class="dash-card">
        <h3>🧩 Continue Editing</h3>
        <p>Resume your last saved project</p>
      </a>

      <a href="payments.html"
        style="display:block; text-decoration:none; color:inherit; cursor:pointer;"
        class="dash-card">
        <h3>💳 Billing</h3>
        <p>View payments & subscription</p>
      </a>

    </div>

    <!-- STATS -->
    <div class="section-title">Your Overview</div>

    <div class="dashboard-grid">
      <div class="stat-card">
        <h1>4</h1>
        <p>Templates Selected</p>
      </div>

      <div class="stat-card">
        <h1>1</h1>
        <p>Website Published</p>
      </div>

      <div class="stat-card">
        <h1>PRO</h1>
        <p>Current Plan</p>
      </div>
    </div>

    <!-- RECENT PROJECTS -->
    <div class="section-title">Recent Projects</div>

    <div class="recent-list">
      <div class="recent-item">
        <div>
          <h4>Wedding Invitation</h4>
          <p>Last edited: Today</p>
        </div>
        <button>Edit</button>
      </div>

      <div class="recent-item">
        <div>
          <h4>Reception Website</h4>
          <p>Status: Published</p>
        </div>
        <button>View</button>
      </div>
    </div>


@endsection
