<!-- resources/views/pdf/preview.blade.php -->
<style>
    .invoice-preview {
        font-family: sans-serif;
        font-size: 13px;
        color: #333;
        line-height: 1.4;
    }
    .invoice-preview h4, .invoice-preview h5 {
        margin-bottom: 8px;
    }
    .invoice-preview table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        table-layout: fixed;
    }
    .invoice-preview td, .invoice-preview th {
        border: 1px solid #ddd;
        padding: 8px;
        vertical-align: top;
        word-wrap: break-word;
    }
    .invoice-preview th {
        background-color: #f5f5f5;
    }
    .invoice-preview .right {
        text-align: right;
    }
    .invoice-preview .center {
        text-align: center;
    }
    .invoice-preview .col-label {
        width: 70%;
    }
    .invoice-preview .col-value {
        width: 30%;
    }
</style>

<div class="invoice-preview">
    <div class="row mb-3">
        <div class="col-6">
            <h4>Invoice #{{ $userBill->invoice_number }}</h4>
            <p>
                <strong>Tanggal Invoice:</strong> {{ $userBill->updated_at->translatedFormat('l, j F Y') }}<br>
                <strong>Periode Tagihan:</strong> {{ \Carbon\Carbon::parse($userBill->billing_month)->translatedFormat('F Y') }}
            </p>
        </div>
        <div class="col-6 text-right">
            <p>
                <strong>Status:</strong>
                <span class="text-{{ $userBill->status === 'paid' ? 'success' : ($userBill->status === 'unpaid' ? 'danger' : ($userBill->status === 'pending' ? 'warning' : 'secondary')) }}">
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
            </p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <strong>Untuk:</strong><br>
            {{ $userBill->user->name }}<br>
            {{ $userBill->user->address }}<br>
            Telp: {{ $userBill->user->phone }}<br>
            Email: {{ $userBill->user->email }}
        </div>
    </div>

    <h5>Rincian Paket</h5>
    <table>
        <thead>
            <tr>
                <th>Paket</th>
                <th>Kecepatan</th>
                <th class="right">Harga</th>
                <th class="right">Diskon</th>
                <th class="right">Total</th>
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

    <h5 class="mt-4">Ringkasan Tagihan</h5>
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

    @if($userBill->payment_method === 'bank_transfer' && $userBill->transfer_proof)
        <div class="text-center mt-4">
            <h5>Bukti Transfer</h5>
            <img src="{{ asset('storage/' . $userBill->transfer_proof) }}" alt="Bukti Transfer" width="250">
        </div>
    @endif

    @if($userBill->status === 'paid')
        <p class="text-center text-muted mt-4">
            Pembayaran telah diterima dan dikonfirmasi oleh sistem.<br>
            Invoice ini sah sebagai bukti pembayaran.
        </p>
    @endif
</div>
