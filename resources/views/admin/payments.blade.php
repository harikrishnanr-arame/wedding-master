@extends('admin.layout')

@section('title', 'Payments')
@section('page-title', 'Payments Management')

@section('content')

<!-- Summary Cards -->
<div class="cards">
    <div class="panel card">
        <h3>Total Revenue</h3>
        <p id="totalRevenue">₹ 0</p>
    </div>
    <div class="panel card">
        <h3>Pending</h3>
        <p id="totalPending">₹ 0</p>
    </div>
    <div class="panel card">
        <h3>Failed</h3>
        <p id="totalFailed">₹ 0</p>
    </div>
</div>

<!-- Table -->
<div class="panel">

    <div class="header-row">
        <input type="text" id="searchPayment" placeholder="Search payments by ID, user, or method...">
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Transaction ID</th>
                <th>User</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="paymentsTableBody"></tbody>
    </table>

</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    let payments = [
        {id:1, txn:"TXN1001", user:"Michael Brown", amount:5000, method:"UPI", status:"Paid", date:"12 Jan 2026"},
        {id:2, txn:"TXN1002", user:"Emily White", amount:3200, method:"Card", status:"Pending", date:"11 Jan 2026"},
        {id:3, txn:"TXN1003", user:"John Smith", amount:1500, method:"Net Banking", status:"Failed", date:"10 Jan 2026"}
    ];

    function updateSummary(data = payments) {
        let totalRevenue = 0;
        let totalPending = 0;
        let totalFailed = 0;

        data.forEach(p => {
            if (p.status === "Paid") totalRevenue += p.amount;
            if (p.status === "Pending") totalPending += p.amount;
            if (p.status === "Failed") totalFailed += p.amount;
        });

        $("#totalRevenue").text("₹ " + totalRevenue);
        $("#totalPending").text("₹ " + totalPending);
        $("#totalFailed").text("₹ " + totalFailed);
    }

    function loadPayments(data = payments) {
        let rows = "";

        if(data.length === 0){
            rows = `<tr><td colspan="8" style="text-align:center; padding:20px;">No payments found.</td></tr>`;
        } else {
            data.forEach((p, index) => {
                let statusClass = p.status.toLowerCase();

                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${p.txn}</td>
                        <td>${p.user}</td>
                        <td>₹ ${p.amount}</td>
                        <td>${p.method}</td>
                        <td><span class="status ${statusClass}">${p.status}</span></td>
                        <td>${p.date}</td>
                        <td class="actions">
                            <button class="edit" data-id="${p.id}">Edit</button>
                            <button class="delete" data-id="${p.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }

        $("#paymentsTableBody").html(rows);
        updateSummary(data);
    }

    // Delete
    $(document).on("click", ".delete", function() {
        let id = $(this).data("id");
        payments = payments.filter(p => p.id !== id);
        loadPayments();
    });

    // Edit
    $(document).on("click", ".edit", function() {
        let id = $(this).data("id");
        let payment = payments.find(p => p.id === id);

        let newAmount = parseInt(prompt("Update Amount:", payment.amount));
        let newStatus = prompt("Update Status:", payment.status);

        if (!newAmount || !newStatus) return;

        payment.amount = newAmount;
        payment.status = newStatus;

        loadPayments();
    });

    // Search functionality
    $("#searchPayment").on("input", function() {
        let value = $(this).val().toLowerCase();

        let filtered = payments.filter(p =>
            p.txn.toLowerCase().includes(value) ||
            p.user.toLowerCase().includes(value)
        );

        loadPayments(filtered);
    });

    loadPayments();

});
</script>

@endsection
