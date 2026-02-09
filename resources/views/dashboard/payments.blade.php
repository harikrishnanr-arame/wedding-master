@extends('layouts.dashboard')

@section('title', 'Payments')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/userDashboard_payments.css') }}">
@endpush

@section('content')
    <div class="page-header">
      <h1>Payments</h1>
      <p>All your template purchases and payment history</p>
    </div>

    <div class="payments-box">
      <table>
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Date</th>
            <th>Template</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Invoice</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>#PAY-12001</td>
            <td>14 Jan 2026</td>
            <td>Portfolio Template</td>
            <td>₹2,499</td>
            <td><span class="status paid">PAID</span></td>
            <td><button class="invoice-btn">Download</button></td>
          </tr>

          <tr>
            <td>#PAY-11942</td>
            <td>02 Jan 2026</td>
            <td>Business Website</td>
            <td>₹3,999</td>
            <td><span class="status pending">PENDING</span></td>
            <td><button class="invoice-btn">Download</button></td>
          </tr>

          <tr>
            <td>#PAY-11811</td>
            <td>18 Dec 2025</td>
            <td>Photography Template</td>
            <td>₹1,999</td>
            <td><span class="status failed">FAILED</span></td>
            <td>—</td>
          </tr>
        </tbody>
      </table>
    </div>

@endsection
