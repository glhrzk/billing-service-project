@extends('layouts.app')
@section('subtitle', 'Tagihan Saya')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Daftar Tagihan Anda" theme="dark" icon="fas fa-money-bill">
        @php
            $heads = ['#', 'Bulan', 'Total Tagihan', 'Status', 'Diskon (Rp)', 'Aksi'];
            $config = [
                'data' => $userBills->map(function ($bill) {
                    $status = strtoupper(payment_status_label($bill->status));
                    $badge = match($bill->status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'unpaid' => 'danger',
                        'rejected' => 'secondary',
                        default => 'light',
                    };

                    return [
                        $bill->id,
                        month_label($bill->billing_month),
                        rupiah_label($bill->amount),
                        "<span class='badge badge-{$badge}'>{$status}</span>",
                        rupiah_label(collect($bill->billItems)->sum('package_discount_amount')),
                        '<a href="'.route('user.bill.show', $bill->id).'" class="btn btn-sm btn-info">Detail</a>',
                    ];
                })->toArray(),

                'order' => [[0, 'asc']],
                'columns' => [['orderable' => false], null, null, null, null, ['orderable' => false]],
            ];
        @endphp

        <x-adminlte-datatable id="tableBillUser" :heads="$heads" :config="$config" striped hoverable bordered compressed
                              beautify/>
    </x-adminlte-card>
@stop
