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

    $("#changePasswordBtn").click(function () {

        let newPass = $("#newPassword").val();
        let confirmPass = $("#confirmPassword").val();

        if (newPass.length < 6) {
            Swal.fire({
                icon: "error",
                title: "Invalid Password",
                text: "Password must be at least 6 characters.",
                confirmButtonColor: "#d33"
            });
            return;
        }

        if (newPass !== confirmPass) {
            Swal.fire({
                icon: "warning",
                title: "Password Mismatch",
                text: "Passwords do not match.",
                confirmButtonColor: "#f39c12"
            });
            return;
        }

        Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Password updated successfully!",
            confirmButtonColor: "#3085d6"
        });

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

