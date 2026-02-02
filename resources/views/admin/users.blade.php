@extends('admin.layout')

@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')

<div class="panel">

    <div class="users-header">
        <input type="text" placeholder="Search users..." id="searchInput">
        <button class="add-btn" id="addUserBtn">+ Add User</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="usersTableBody"></tbody>
    </table>

</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    // Fake Database
    let users = [
        {id:1, name:"Michael Brown", email:"michael@example.com", role:"Admin", status:"Active", joined:"12 Jan 2026"},
        {id:2, name:"Emily White", email:"emily@example.com", role:"User", status:"Active", joined:"10 Jan 2026"},
        {id:3, name:"John Smith", email:"john@example.com", role:"User", status:"Inactive", joined:"08 Jan 2026"},
        {id:4, name:"Sophia Johnson", email:"sophia@example.com", role:"User", status:"Active", joined:"05 Jan 2026"}
    ];

    function loadUsers(data = users) {
        let rows = "";

        if (data.length === 0) {
            rows = `<tr><td colspan="7" style="text-align:center; color:#777;">No matching users found</td></tr>`;
        } else {
            data.forEach((user, index) => {
                let statusClass = user.status === "Active" ? "active" : "inactive";

                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td><span class="status ${statusClass}">${user.status}</span></td>
                        <td>${user.joined}</td>
                        <td class="actions">
                            <button class="delete" data-id="${user.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }

        $("#usersTableBody").html(rows);
    }

    // Search
    $("#searchInput").on("input", function() {
        let value = $(this).val().toLowerCase();

        let filtered = users.filter(user =>
            user.name.toLowerCase().includes(value) ||
            user.email.toLowerCase().includes(value)
        );

        loadUsers(filtered);
    });

    // Delete User
    $(document).on("click", ".delete", function() {
        let id = $(this).data("id");
        users = users.filter(user => user.id !== id);
        loadUsers();
    });

    // Add User
    $("#addUserBtn").click(function() {

        let name = prompt("Enter Name:");
        let email = prompt("Enter Email:");
        let role = prompt("Enter Role (Admin/User):");

        if (!name || !email || !role) {
            alert("All fields required!");
            return;
        }

        let newUser = {
            id: users.length ? users[users.length - 1].id + 1 : 1,
            name: name,
            email: email,
            role: role,
            status: "Active",
            joined: new Date().toLocaleDateString()
        };

        users.push(newUser);
        loadUsers();
    });

    // Initial Load
    loadUsers();

});
</script>

@endsection
