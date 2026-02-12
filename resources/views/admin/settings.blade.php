@extends('admin.layout')

@section('title', 'Admin - Settings')

@section('content')

<div class="main">

    <div class="topbar">
        <h2>Settings</h2>
    </div>

    <!-- Profile Panel -->
    <div class="panel">
        <h3>Admin Profile</h3>

        <div class="profile-section">
            <div class="profile-img" id="profileInitial">
                {{ strtoupper(substr($admin->user_name, 0, 1)) }}
            </div>

            <div class="profile-info">
                <p><strong>Name:</strong> 
                    <span id="adminName">{{ $admin->user_name }}</span>
                </p>

                <p><strong>Email:</strong> 
                    <span id="adminEmail">{{ $admin->email }}</span>
                </p>

                <p><strong>Role:</strong> 
                    {{ ucfirst($admin->role) }}
                </p>
                <!-- <button class="edit-btn" id="editProfile">Edit Profile</button> -->
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="panel">
        <h3>Change Password</h3>

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" id="currentPassword">
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" id="newPassword">
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" id="confirmPassword">
        </div>

        <button class="save-btn" id="changePasswordBtn">Update Password</button>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/admin-settings.js') }}"></script>
@endsection

