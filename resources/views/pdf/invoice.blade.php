<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $userBill->invoice_number }}</title>
    <style>
        @page {
            margin: 30px 40px 30px 40px;
        }

        body {
            font-family: sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }

        .section {
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }

        td, th {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background-color: #f5f5f5;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .col-label {
            width: 70%;
        }

        .col-value {
            width: 30%;
        }

        .status-paid {
            color: green;
            font-weight: bold;
        }

        .status-unpaid {
            color: red;
            font-weight: bold;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-rejected {
            color: gray;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Invoice #{{ $userBill->invoice_number }}</h2>
<p>
    <strong>Tanggal Invoice:</strong> {{ $userBill->updated_at->translatedFormat('l, j F Y') }}<br>
    <strong>Periode Tagihan:</strong> {{ \Carbon\Carbon::parse($userBill->billing_month)->translatedFormat('F Y') }}
</p>

<div class="section">
    <table>
        <tr>
            <td width="50%">
                <strong>Untuk:</strong><br>
                {{ $userBill->user->name }}<br>
                {{ $userBill->user->address }}<br>
                Telp: {{ $userBill->user->phone }}<br>
                Email: {{ $userBill->user->email }}
            </td>
            <td width="50%">
                <strong>Status:</strong>
                <span class="status-{{ $userBill->status }}">
                    {{ strtoupper(payment_status_label($userBill->status)) }}
                </span><br>
                <strong>Jatuh Tempo:</strong> Tanggal {{ $userBill->user->due_date }}<br>
                <strong>Metode:</strong> {{ ucfirst(str_replace('_', ' ', $userBill->payment_method)) }}<br>
                @if($userBill->payment_method === 'bank_transfer')
                    <strong>Tanggal Transfer:</strong> {{ $userBill->transfer_date ?? '-' }}<br>
                @endif
                @if($userBill->status === 'paid')
                    <strong>Dibayar:</strong> {{ \Carbon\Carbon::parse($userBill->paid_at)->translatedFormat('l, j F Y') }}
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="section">
    <h4>Rincian Paket</h4>
    <table>
        <thead>
        <tr>
            <th>Paket</th>
            <th>Kecepatan</th>
            <th>Harga</th>
            <th>Diskon</th>
            <th>Total</th>
            <th>Alasan Diskon</th>
        </tr>
        </thead>
        <tbody>
        @foreach($userBill->billItems as $item)
            <tr>
                <td>{{ $item->billed_package_name }}</td>
                <td>{{ $item->billed_package_speed }}</td>
                <td class="right">{{ rupiah_label($item->billed_package_price) }}</td>
                <td class="right">{{ $item->package_discount_amount ? rupiah_label($item->package_discount_amount) : 'Rp 0' }}</td>
                <td class="right">{{ rupiah_label($item->final_amount) }}</td>
                <td>{{ $item->package_discount_reason ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @php
        $totalOriginal = $userBill->billItems->sum('billed_package_price');
        $totalPackageDiscount = $userBill->billItems->sum('package_discount_amount');
    @endphp

    <table style="margin-top: 10px;">
        <colgroup>
            <col class="col-label">
            <col class="col-value">
        </colgroup>
        <tr>
            <td><strong>Total Harga Paket Sebelum Diskon:</strong></td>
            <td class="right">{{ rupiah_label($totalOriginal) }}</td>
        </tr>
        <tr>
            <td><strong>Total Diskon Paket:</strong></td>
            <td class="right">-{{ rupiah_label($totalPackageDiscount) }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <h4>Ringkasan Tagihan</h4>
    <table>
        <colgroup>
            <col class="col-label">
            <col class="col-value">
        </colgroup>
        <tr>
            <td>Subtotal Paket</td>
            <td class="right">{{ rupiah_label($userBill->amount) }}</td>
        </tr>
        @if($userBill->discount_amount > 0)
            <tr>
                <td>Diskon Insidental ({{ $userBill->discount_reason }})</td>
                <td class="right">-{{ rupiah_label($userBill->discount_amount) }}</td>
            </tr>
        @endif
        <tr>
            <td><strong>Total yang Harus Dibayar</strong></td>
            <td class="right"><strong>{{ rupiah_label($userBill->final_amount) }}</strong></td>
        </tr>
    </table>
</div>

@if($userBill->payment_method === 'bank_transfer' && $userBill->transfer_proof)
    <div class="section">
        <h4>Bukti Transfer</h4>
        <div class="center">
            <img src="{{ public_path('storage/' . $userBill->transfer_proof) }}" width="250">
        </div>
    </div>
@endif

@if($userBill->status === 'paid')
    <p style="margin-top: 20px; font-size: 12px; text-align: center;">
        Pembayaran telah diterima dan dikonfirmasi oleh sistem. Invoice ini sah sebagai bukti pembayaran.
    </p>
@endif

</body>
</html>
