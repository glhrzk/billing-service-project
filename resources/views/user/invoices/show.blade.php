{{--@dd($user, $userBill, $packageBillsGroup)--}}
@extends('layouts.app')

@section('title', 'Invoice')

@section('content_body')

    <div class="invoice p-3 mb-3">
        <!-- Title Row -->
        <div class="row">
            <div class="col-12">
                <h4>
                    <i class="fas fa-globe"></i> {{ env('APP_NAME') }}
                    <small
                        class="float-right">Tanggal: {{ \Carbon\Carbon::parse($userBill->billing_month)->locale('id')->translatedFormat('d F Y') }}</small>
                </h4>
            </div>
        </div>

        <!-- Info Row -->
        <div class="row invoice-info mt-4">
            <div class="col-sm-4 invoice-col">
                Dari:
                <address>
                    <strong> {{ env('COMPANY_NAME') }} </strong><br>
                    {{ env('COMPANY_ADDRESS') }}<br>
                    {{ env('COMPANY_PHONE') }}<br>
                    {{ env('COMPANY_EMAIL') }}<br>
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                Untuk:
                <address>
                    <strong>{{ $user->name }}</strong><br>
                    {{ $user->address }}<br>
                    Phone: {{ $user->phone }}<br>
                    Email: {{ $user->email }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Invoice #{{ $userBill->invoice_number }}</b><br>
                <br>
                <b>Status:</b> {{ payment_status_label($userBill->status) }}<br>

                <b>Jatuh Tempo:</b> Tanggal {{ $user->due_date }}
            </div>
        </div>

        <!-- Table Row -->
        <div class="row mt-4">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Kecepatan</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($packageBillsGroup as $packageBill)
                        <tr>
                            <td>{{ $packageBill->locked_name }}</td>
                            <td>{{ $packageBill->locked_speed }}</td>
                            <td>{{ rupiah_label($packageBill->locked_price) }}</td>
                            <td>{{ rupiah_label($packageBill->discount_amount) }}</td>
                            <td>{{ rupiah_label($packageBill->final_amount) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Methods Row -->
        <div class="row mt-4">
            <div class="col-6">
                <p class="lead">Metode Pembayaran:</p>
                <div class="mb-3">
                    <i class="fas fa-university text-primary fa-lg mr-2"></i>
                    <span style="margin-right: 20px;">Transfer Bank</span>

                    <i class="fas fa-money-bill-alt text-success fa-lg mr-2"></i>
                    <span>Cash</span>
                </div>
                <p class="text-muted mt-3">Harap melakukan pembayaran sebelum tanggal jatuh tempo.</p>
            </div>
            <div class="col-6">
                <p class="lead">Total Tagihan</p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Total:</th>
                            <td>{{ rupiah_label($userBill->amount) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Buttons Row -->
        <div class="row no-print mt-3">
            <div class="col-12">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary"><i class="fas fa-list"></i> Kembali</a>
                <a href="{{ route('user.invoice.download', $userBill->id) }}" class="btn btn-primary"><i
                        class="fas fa-download"></i> Download PDF</a>
                <button onclick="window.print();" class="btn btn-success float-right"><i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

    </div>

@stop
