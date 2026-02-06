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
        <tbody id="paymentsTableBody">
            @if($payments->count())
                @foreach($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>TXN{{ 1000 + $payment->id }}</td>
                        <td>{{ $payment->user->user_name ?? 'N/A' }}</td>
                        <td>₹ {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_provider }}</td>
                        <td>
                            <span class="status {{ strtolower($payment->status) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->created_at->format('d M Y') }}</td>
                        <td class="actions">
                            <button class="delete"></button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" style="text-align:center;padding:20px;">
                        No payments found.
                    </td>
                </tr>
            @endif
        </tbody>

    </table>

</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    let allPayments = [];

    // Load payments
    function loadPayments() {
        $.get('/admin/payments/list', function(data) {
            allPayments = data;
            renderPayments(allPayments);
            updateTotals(allPayments);
        });
    }

    // Render table
    function renderPayments(payments) {
        let rows = '';

        if (payments.length === 0) {
            rows = `<tr>
                        <td colspan="8" style="text-align:center;padding:20px;">
                            No payments found.
                        </td>
                    </tr>`;
        } else {
            payments.forEach((p, index) => {
                rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>TXN${1000 + p.id}</td>
                        <td>${p.user ? p.user.user_name : 'N/A'}</td>
                        <td>₹ ${parseFloat(p.amount).toFixed(2)}</td>
                        <td>${p.payment_provider ?? '-'}</td>
                        <td>
                            <span class="status ${p.status.toLowerCase()}">
                                ${p.status.charAt(0).toUpperCase() + p.status.slice(1)}
                            </span>
                        </td>
                        <td>${new Date(p.created_at).toLocaleDateString()}</td>
                        <td>
                            <button class="delete" data-id="${p.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        $("#paymentsTableBody").html(rows);
    }

    // Update summary totals
    function updateTotals(payments) {
        let totalRevenue = 0;
        let totalPending = 0;
        let totalFailed  = 0;

        payments.forEach(p => {
            const status = p.status.toLowerCase();

            if (status === 'paid') totalRevenue += parseFloat(p.amount);
            if (status === 'pending') totalPending += parseFloat(p.amount);
            if (status === 'failed') totalFailed += parseFloat(p.amount);
        });

        $("#totalRevenue").text('₹ ' + totalRevenue.toFixed(2));
        $("#totalPending").text('₹ ' + totalPending.toFixed(2));
        $("#totalFailed").text('₹ ' + totalFailed.toFixed(2));
    }

    // Delete payment
    $(document).on("click", ".delete", function() {

        let id = $(this).data("id");

        if(!confirm("Are you sure you want to delete this payment?")) return;

        $.ajax({
            url: `/admin/payments/delete/${id}`,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function() {
                allPayments = allPayments.filter(p => p.id !== id);
                renderPayments(allPayments);
                updateTotals(allPayments);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Delete failed!");
            }
        });
    });

    // Search
    $("#searchPayment").on("input", function() {

        const search = $(this).val().toLowerCase();

        const filtered = allPayments.filter(p => {
            const txn = 'TXN' + (1000 + p.id);
            const user = p.user ? p.user.user_name.toLowerCase() : '';
            const method = p.payment_provider ? p.payment_provider.toLowerCase() : '';

            return txn.toLowerCase().includes(search)
                || user.includes(search)
                || method.includes(search);
        });

        renderPayments(filtered);
        updateTotals(filtered);
    });

    loadPayments();
});
</script>

@endsection