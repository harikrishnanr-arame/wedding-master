$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

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