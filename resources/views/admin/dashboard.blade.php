@extends('layouts.app')

@section('content')
<div style="padding:40px">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ auth()->user()->user_name }}</p>
</div>
@endsection
