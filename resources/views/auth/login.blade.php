@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
@endsection