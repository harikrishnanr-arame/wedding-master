@extends('admin.layout')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')

<div class="panel">
    <div class="users-header">
        <input type="text" placeholder="Search users..." id="searchInput">
            <button id="addUserBtn" style="padding:8px 15px; background:#4e8cff; color:white; border:none; border-radius:6px;">
        + Add User
    </button>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="usersTableBody"></tbody>
    </table>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal-overlay">
        <div class="modal-box">

            <div class="modal-header">
                <h3>Add New User</h3>
                <span class="close-modal" id="closeModal">&times;</span>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label for="newName">Name</label>
                    <input 
                        type="text" 
                        id="newName" 
                        name="name"
                        placeholder="Enter name">
                </div>

                <div class="form-group">
                    <label for="newEmail">Email</label>
                    <input 
                        type="email" 
                        id="newEmail" 
                        name="email"
                        placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label for="newPassword">Password</label>
                    <input 
                        type="password" 
                        id="newPassword" 
                        name="password"
                        placeholder="Enter password"
                        autocomplete="new-password">
                </div>

                <div class="form-group">
                    <label for="newRole">Role</label>
                    <select id="newRole" name="role">
                        <option value="0">User</option>
                        <option value="1">Admin</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn-secondary" id="closeModalBtn">Cancel</button>
                <button class="btn-primary" id="saveUser">Create User</button>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    // Load Users
    function loadUsers() {
        $.ajax({
            url: "{{ route('admin.users.list') }}",
            type: "GET",
            success: function(data) {

                let rows = "";

                if (data.length === 0) {
                    rows = `<tr>
                        <td colspan="6" style="text-align:center;">No users found</td>
                    </tr>`;
                } else {
                    data.forEach((user, index) => {

                        let joinedDate = user.created_at 
                            ? new Date(user.created_at).toLocaleDateString()
                            : '-';

                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${user.user_name}</td>
                                <td>${user.email}</td>
                                <td>${user.role ?? 'user'}</td>
                                <td>${joinedDate}</td>
                                <td>
                                    <button class="delete" data-id="${user.id}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }

                $("#usersTableBody").html(rows);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to load users',
                    text: 'Something went wrong.'
                });
            }
        });
    }

    // DELETE
    $(document).on("click", ".delete", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This user will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ url('admin/users/delete') }}/" + id,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if (response.error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: response.error
                            });
                        } else {
                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: "User has been deleted successfully.",
                                timer: 1500,
                                showConfirmButton: false
                            });

                            loadUsers();
                        }

                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong. Please try again."
                        });
                    }
                });

            }

        });

    });

    // SEARCH FILTER
    $("#searchInput").on("keyup", function () {

        let value = $(this).val().toLowerCase().trim();
        let rows = $("#usersTableBody tr").not("#noDataRow");
        let matchCount = 0;

        rows.each(function () {

            let name = $(this).find("td:eq(1)").text().toLowerCase();
            let email = $(this).find("td:eq(2)").text().toLowerCase();

            if (name.includes(value) || email.includes(value)) {
                $(this).show();
                matchCount++;
            } else {
                $(this).hide();
            }

        });

        $("#noDataRow").remove();

        if (matchCount === 0 && value !== "") {
            $("#usersTableBody").append(`
                <tr id="noDataRow">
                    <td colspan="6" style="text-align:center;">No users found</td>
                </tr>
            `);
        }

    });

    // OPEN MODAL
    $("#addUserBtn").click(function(){
        $("#addUserModal").css("display","flex");
    });

    // CLOSE MODAL
    $("#closeModal, #closeModalBtn").click(function(){
        $("#addUserModal").hide();
    });

    // SAVE USER (SweetAlert Success & Error)
    $("#saveUser").click(function(){

        $.ajax({
            url: "{{ route('admin.users.store') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_name: $("#newName").val(),
                email: $("#newEmail").val(),
                password: $("#newPassword").val(),
                role: $("#newRole").val()
            },
            success: function(response){

                $("#addUserModal").hide();

                // Clear form
                $("#newName").val('');
                $("#newEmail").val('');
                $("#newPassword").val('');
                $("#newRole").val('0');

                Swal.fire({
                    icon: 'success',
                    title: 'User Created!',
                    text: 'New user has been added successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });

                loadUsers();
            },
            error: function(xhr){

                Swal.fire({
                    icon: 'error',
                    title: 'Creation Failed',
                    text: xhr.responseJSON?.message ?? 'Something went wrong!'
                });

            }
        });

    });

    loadUsers();
});
</script>

@endsection
