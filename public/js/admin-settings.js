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
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Password must be at least 6 characters.'
            });
            return;
        }

        if(newPass !== confirmPass){
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Passwords do not match.'
            });
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Password updated successfully!',
            confirmButtonColor: '#3085d6'
        });
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
            Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.success
            });
            $("input[type=password]").val("");
        },
        error: function(xhr){
            Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.error || "Something went wrong"
            });
        }
    });

});
