@extends('layouts.app')
@section('subtitle', 'Daftar Tagihan')
@section('plugins.Datatables', true)

@section('content_body')

    <x-adminlte-card title="Daftar Tagihan" theme="dark" icon="fas fa-money-bill">
        <form method="GET">
            <div class="row">
                <div class="col-md-2">
                    <x-adminlte-select name="year" label="Tahun">
                        <option value="">Semua</option>
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-2">
                    <x-adminlte-select name="month" label="Bulan">
                        <option value="">Semua</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ (int)request('month') === $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </x-adminlte-select>
                </div>

                <div class="col-md-2">
                    <x-adminlte-select name="status" label="Status" class="form-control">
                        <option value="">Semua</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak
                        </option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <x-adminlte-input name="user" label="Nama User" value="{{ request('user') }}"/>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <x-adminlte-button label="Filter" theme="primary" type="submit" icon="fas fa-search" class="w-100"/>
                </div>
            </div>
        </form>
        @php
            $heads = ['#', 'User', 'Bulan', 'Tagihan', 'Status', 'Diskon (Rp)', 'Aksi'];
            $config = [
                'data' => $userBills->map(function ($userBills) {
                    $status = strtoupper(payment_status_label($userBills->status));
                    $badge = match($userBills->status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'unpaid' => 'danger',
                        default => 'secondary',
                    };

                    return [
                        $userBills->id,
                        $userBills->user->name ?? '-',
                        \Carbon\Carbon::parse($userBills->billing_month)->translatedFormat('F Y'),
                        rupiah_label($userBills->amount),
                        "<span class='badge badge-{$badge}'>{$status}</span>",
                        rupiah_label(collect($userBills->packageBills)->sum('discount_amount')),
                        '<a href="'.route('admin.bills.show', $userBills->id).'" class="btn btn-sm btn-info">Detail</a>',
                    ];
                })->toArray(),

                'order' => [[0, 'asc']],
                'columns' => [['orderable' => false], null, null, null, null, null, ['orderable' => false]],
            ];
        @endphp

        <x-adminlte-datatable id="tableBill" :heads="$heads" :config="$config" striped hoverable bordered compressed
                              beautify/>
    </x-adminlte-card>
@stop
