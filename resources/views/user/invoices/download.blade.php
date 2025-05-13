<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $userBill->invoice_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            margin: 30px;
            color: #333;
        }

        h1, h4 {
            margin: 0;
            padding: 0;
        }

        .section {
            margin-top: 25px;
        }

        .info-table, .package-table, .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td, .package-table th, .package-table td, .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .package-table th {
            background-color: #f5f5f5;
        }

        .right {
            text-align: right;
        }

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-unpaid {
            color: red;
            font-weight: bold;
        }

        /* somewhere in your app’s CSS (e.g. public/css/app.css) */
        .bg-green {
            color: green;
            font-weight: bold;
        }

        .bg-gray {
            color: gray;
            font-weight: bold;
        }

        .bg-red {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Invoice #{{ $userBill->invoice_number }}</h2>
<p>
    <strong>Tanggal Invoice:</strong> {{ \Carbon\Carbon::parse($userBill->updated_at)->translatedFormat('d F Y') }}<br>
    <strong>Periode Tagihan:</strong> {{ \Carbon\Carbon::parse($userBill->billing_month)->translatedFormat('F Y') }}
</p>

<div class="section">
    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Untuk:</strong><br>
                {{ $user->name }}<br>
                {{ $user->address }}<br>
                Telp: {{ $user->phone }}<br>
                Email: {{ $user->email }}
            </td>
            <td width="50%">
                <strong>Status:</strong>
                <span class="{{ payment_status_badge($userBill->status) }}">
                    {{ payment_status_label($userBill->status) }}
                </span>
                <br>
                <strong>Jatuh Tempo:</strong> Tanggal {{ $user->due_date }}<br>
                <strong>Metode:</strong> {{ ucfirst(str_replace('_',' ', $userBill->payment_method)) }}<br>
                @if($userBill->status === 'paid')
                    <strong>Dibayar:</strong> {{ \Carbon\Carbon::parse($userBill->paid_at)->translatedFormat('d F Y') }}
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <h4>Rincian Paket</h4>
    <table class="package-table">
        <thead>
        <tr>
            <th>Paket</th>
            <th>Kecepatan</th>
            <th>Harga</th>
            <th>Diskon</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($packageBillsGroup as $pb)
            <tr>
                <td>{{ $pb->locked_name }}</td>
                <td>{{ $pb->locked_speed }}</td>
                <td class="right">{{ rupiah_label($pb->locked_price) }}</td>
                <td class="right">{{ rupiah_label($pb->discount_amount) }}</td>
                <td class="right">{{ rupiah_label($pb->final_amount) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <table class="summary-table">
        <tr>
            <td><strong>Total Tagihan:</strong></td>
            <td class="right"><strong>{{ rupiah_label($userBill->amount) }}</strong></td>
        </tr>
    </table>
</div>
@if($userBill->status === 'paid')
    <p style="margin-top: 20px; font-size: 12px; text-align: center;">
        Pembayaran telah diterima dan dikonfirmasi oleh sistem. Invoice ini sah sebagai bukti pembayaran.
    </p>
@endif

</body>
</html>
