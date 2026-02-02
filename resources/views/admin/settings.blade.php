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
            <div class="profile-img" id="profileInitial">A</div>

            <div class="profile-info">
                <p><strong>Name:</strong> <span id="adminName">Aswin Admin</span></p>
                <p><strong>Email:</strong> <span id="adminEmail">admin@example.com</span></p>
                <p><strong>Role:</strong> Super Admin</p>

                <button class="edit-btn" id="editProfile">Edit Profile</button>
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

    <!-- General Settings -->
    <div class="panel">
        <h3>General Settings</h3>

        <div class="toggle">
            <span>Email Notifications</span>
            <label class="switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
            </label>
        </div>

        <div class="toggle">
            <span>Enable Dark Mode</span>
            <label class="switch">
                <input type="checkbox" id="darkModeToggle">
                <span class="slider"></span>
            </label>
        </div>

        <div class="toggle">
            <span>Two-Factor Authentication</span>
            <label class="switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
        </div>

    </div>

</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function(){

    $("#editProfile").click(function(){

        let newName = prompt("Enter new name:", $("#adminName").text());
        let newEmail = prompt("Enter new email:", $("#adminEmail").text());

        if(newName) {
            $("#adminName").text(newName);
            $("#profileInitial").text(newName.charAt(0).toUpperCase());
        }

        if(newEmail){
            $("#adminEmail").text(newEmail);
        }
    });

    $("#changePasswordBtn").click(function(){

        let newPass = $("#newPassword").val();
        let confirmPass = $("#confirmPassword").val();

        if(newPass.length < 6){
            alert("Password must be at least 6 characters.");
            return;
        }

        if(newPass !== confirmPass){
            alert("Passwords do not match.");
            return;
        }

        alert("Password updated successfully!");
        $("input[type=password]").val("");
    });

    $("#darkModeToggle").change(function(){
        if($(this).is(":checked")){
            $("body").css("background","#1e1e2f");
            $(".panel").css("background","#2b2b3c").css("color","white");
        }else{
            $("body").css("background","#eef2f7");
            $(".panel").css("background","white").css("color","#000");
        }
    });

});
</script>
@endsection
