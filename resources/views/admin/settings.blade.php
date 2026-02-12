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
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="panel">
        <h3>Change Password</h3>

        <div class="form-group">
            <label for="currentPassword">Current Password</label>
            <input type="password" id="currentPassword" name="current_password">
        </div>

        <div class="form-group">
            <label for="newPassword">New Password</label>
            <input type="password" id="newPassword" name="new_password">
        </div>

        <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirm_password">
        </div>

        <button class="save-btn" id="changePasswordBtn">Update Password</button>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('js/admin-settings.js') }}"></script>
@endsection

