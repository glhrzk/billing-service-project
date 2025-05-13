@extends('layouts.app')
@section('subtitle', 'Detail Tagihan')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Informasi Tagihan" theme="info" icon="fas fa-file-invoice">
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-input name="user_name" label="Nama User" value="{{ $userBill->user->name }}" disabled/>
            </div>
            <div class="col-md-3">
                <x-adminlte-input name="billing_month" label="Bulan Tagihan"
                                  value="{{ \Carbon\Carbon::parse($userBill->billing_month)->translatedFormat('F Y') }}"
                                  disabled/>
            </div>
            <div class="col-md-3">
                @php
                    $badge = match($userBill->status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'unpaid' => 'danger',
                        default => 'secondary',
                    };
                @endphp
                <x-adminlte-input name="status" label="Status Pembayaran"
                                  value="{{ strtoupper($userBill->status) }}"
                                  igroup-size="sm" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-{{ $badge }}">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="col-md-6">
                <x-adminlte-input name="amount" label="Total Tagihan (Sebelum Diskon)"
                                  value="{{ rupiah_label($userBill->amount) }}" disabled/>
            </div>
            <div class="col-md-6">
                <x-adminlte-input name="total_discount" label="Total Diskon (Rp)"
                                  value="{{ rupiah_label($userBill->packageBills->sum('discount_amount')) }}" disabled/>
            </div>
        </div>
    </x-adminlte-card>

    <x-adminlte-card title="Rincian Paket dalam Tagihan" theme="dark" icon="fas fa-box">
        @php
            $heads = ['#', 'Paket', 'Harga', 'Diskon', 'Harga Akhir', 'Alasan Diskon'];
            $config = [
                'data' => $userBill->packageBills->map(function ($packageBills, $i) {
                    $hargaAkhir = $packageBills->price - $packageBills->discount_amount;
                    return [
                        $i + 1,
                        $packageBills->locked_name ?? '-',
                        $packageBills->locked_price,
                        $packageBills->discount_amount,
                        $packageBills->final_amount,
                        $packageBills->discount_reason ?? '-',
                    ];
                })->toArray(),
                'columns' => [['orderable' => false], null, null, null, null, null],
                'order' => [[0, 'asc']],
            ];
        @endphp

        <x-adminlte-datatable id="tableDetail" :heads="$heads" :config="$config" striped hoverable bordered compressed
                              beautify/>
    </x-adminlte-card>

    <x-adminlte-card title="Aksi" theme="light" icon="fas fa-cogs">
        <a href="{{ route('admin.bills.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tagihan
        </a>
        <a href="{{ route('admin.bills.index', $userBill->id) }}" class="btn btn-success ml-2">
            <i class="fas fa-file-download"></i> Download Invoice
        </a>
    </x-adminlte-card>
@endsection
