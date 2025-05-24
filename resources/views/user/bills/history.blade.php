@extends('layouts.app')
@section('subtitle', 'Riwayat Pembayaran')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Riwayat Pembayaran Anda" theme="dark" icon="fas fa-money-check-alt">
        <form method="GET">
            <div class="row">
                <div class="col-md-3">
                    <x-adminlte-select name="year" label="Tahun">
                        <option value="">Semua</option>
                        @foreach($availableYears as $year)
                            <option
                                value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="month" label="Bulan">
                        <option value="">Semua</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ (int)request('month') === $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <x-adminlte-button label="Filter" theme="primary" type="submit" icon="fas fa-search" class="w-100"/>
                </div>
            </div>
        </form>

        @php
            $heads = ['#', 'Bulan', 'Metode Pembayaran', 'Tanggal Transfer', 'Nominal', 'Bukti', 'Aksi'];
            $config = [
                'data' => $paidBills->map(function ($bill, $i) {
                    return [
                        $i + 1,
                        month_label($bill->billing_month),
                        ucfirst($bill->payment_method),
                        $bill->transfer_date ? \Carbon\Carbon::parse($bill->transfer_date)->format('d M Y') : '-',
                        rupiah_label($bill->final_amount),
                        $bill->transfer_proof
                            ? '<a href="' . asset('storage/' . $bill->transfer_proof) . '" target="_blank" class="btn btn-sm btn-outline-success">Lihat</a>'
                            : '-',
                        '<a href="' . route('user.bill.show', $bill->id) . '" class="btn btn-sm btn-info">Detail</a>',
                    ];
                })->toArray(),
                'columns' => [['orderable' => false], null, null, null, null, ['orderable' => false], ['orderable' => false]],
                'order' => [[0, 'asc']],
            ];
        @endphp

        <x-adminlte-datatable id="tablePaidUser" :heads="$heads" :config="$config" striped hoverable bordered compressed
                              beautify/>
    </x-adminlte-card>
@stop
