@extends('layouts.app')
@section('subtitle', 'Verifikasi Pembayaran')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Daftar Pembayaran Menunggu Verifikasi" theme="warning" icon="fas fa-clock">
        @php
            $heads = ['#', 'Nama User', 'Bulan Tagihan', 'Total Tagihan', 'Metode Pembayaran', 'Bukti Transfer', 'Aksi'];
            $config = [
                'data' => $userBills->map(function ($userBill, $i) {
                    return [
                        $i + 1,
                        $userBill->user->name,
                        \Carbon\Carbon::parse($userBill->billing_month)->translatedFormat('F Y'),
                        rupiah_label($userBill->finalAmount),
                        ucfirst($userBill->payment_method ?? '-'),
                        $userBill->transfer_proof
                            ? '<a href="'.asset('storage/' . $userBill->transfer_proof).'" target="_blank">Lihat</a>'
                            : '-',
                        '<a href="'.route('admin.bills.verify', $userBill->id).'" class="btn btn-sm btn-primary">
                            <i class="fas fa-check-circle"></i> Verifikasi</a>',
                    ];
                })->toArray(),
                'order' => [[0, 'asc']],
                'columns' => [['orderable' => false], null, null, null, null, null, ['orderable' => false]],
            ];
        @endphp

        <x-adminlte-datatable id="tableVerification" :heads="$heads" :config="$config"
                              striped hoverable bordered compressed beautify/>
    </x-adminlte-card>
@endsection
