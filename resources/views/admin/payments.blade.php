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
<script src="{{ asset('js/admin-payments.js') }}"></script>
@endsection
