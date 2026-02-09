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

});

$("#changePasswordBtn").click(function(){

    $.ajax({
        url: "/admin/change-password",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            current_password: $("#currentPassword").val(),
            new_password: $("#newPassword").val(),
            new_password_confirmation: $("#confirmPassword").val()
        },
        success: function(response){
            alert(response.success);
            $("input[type=password]").val("");
        },
        error: function(xhr){
            alert(xhr.responseJSON.error || "Something went wrong");
        }
    });

});
