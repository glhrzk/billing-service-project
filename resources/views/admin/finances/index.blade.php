@extends('layouts.app')
@section('subtitle', 'Riwayat Keuangan')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Riwayat Keuangan" theme="dark" icon="fas fa-coins">

        {{-- Filter --}}
        <form method="GET" class="form-inline mb-3">
            <div class="form-group mr-4">
                <label for="year" class="mr-2">Tahun</label>
                <select name="year" id="year" class="form-control">
                    <option value="">Semua</option>
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mr-4">
                <label for="month" class="mr-2">Bulan</label>
                <select name="month" id="month" class="form-control">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ (int)$selectedMonth === $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mr-4">
                <label for="type" class="mr-2">Jenis</label>
                <select name="type" id="type" class="form-control">
                    <option value="">Semua</option>
                    <option value="income" {{ $selectedType == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ $selectedType == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <div class="form-group mr-4">
                <x-adminlte-button label="Filter" theme="primary" type="submit" icon="fas fa-search"/>
            </div>

            <div class="form-group ml-auto">
                <x-adminlte-button label="Tambah Pengeluaran" theme="danger" icon="fas fa-plus"
                                   data-toggle="modal" data-target="#modalAddExpense"/>
            </div>
        </form>


        <div class="row mb-4 mt-3">
            <div class="col-md-4">
                <x-adminlte-info-box title="Total Pemasukan" text="Rp{{ number_format($totalIncome, 0, ',', '.') }}"
                                     icon="fas fa-arrow-circle-down" theme="success" icon-theme="white"/>
            </div>
            <div class="col-md-4">
                <x-adminlte-info-box title="Total Pengeluaran" text="Rp{{ number_format($totalExpense, 0, ',', '.') }}"
                                     icon="fas fa-arrow-circle-up" theme="danger" icon-theme="white"/>
            </div>
            <div class="col-md-4">
                <x-adminlte-info-box title="Saldo Bersih" text="Rp{{ number_format($balance, 0, ',', '.') }}"
                                     icon="fas fa-wallet" theme="primary" icon-theme="white"/>
            </div>
        </div>

        {{-- Tabel --}}
        @php
            $heads = ['#', 'Tanggal', 'Jenis', 'Deskripsi', 'Nominal'];

            $config = [
                'data' => $transactions->map(function ($trx, $i) {
                    $badge = $trx->type === 'income' ? 'success' : 'danger';
                    $label = $trx->type === 'income' ? 'Pemasukan' : 'Pengeluaran';

                    $description = $trx->description;

                    preg_match('/(\d{4}-\d{2}-\d{2})/', $description, $match);
                    if (isset($match[1])) {
                    $formattedDate = \Carbon\Carbon::parse($match[1])->translatedFormat('F Y');
                    $finalDesc = str_replace($match[1], $formattedDate, $trx->description);
                    } else {
                        $finalDesc = $trx->description;
                    }

                    return [
                        $i + 1,
                        \Carbon\Carbon::parse($trx->date)->translatedFormat('d F Y H:i'),
                        "<span class='badge badge-{$badge}'>{$label}</span>",
                        $finalDesc,
                        'Rp' . number_format($trx->amount, 0, ',', '.'),
                    ];
                })->toArray(),
                'order' => [[1, 'desc']],
                'columns' => [
                    ['orderable' => false],
                    null,
                    null,
                    null,
                    null
                ],
            ];
        @endphp

        <x-adminlte-datatable id="tableTransactions" :heads="$heads" :config="$config"
                              striped hoverable bordered compressed beautify/>
    </x-adminlte-card>

    <form action="{{ route('admin.expenses.store') }}" method="POST">
        <x-adminlte-modal id="modalAddExpense" title="Tambah Pengeluaran" theme="danger" icon="fas fa-minus-circle">
            @csrf
            @method('POST')

            <x-adminlte-input name="date" label="Tanggal" type="date"
                              value="{{ old('date', now()->toDateString()) }}" required/>

            <x-adminlte-input name="amount" label="Jumlah (Rp)" type="number" min="1"
                              value="{{ old('amount') }}" required/>

            <x-adminlte-textarea name="description" label="Deskripsi" required>
                {{ old('description') }}
            </x-adminlte-textarea>

            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" theme="success" label="Simpan" icon="fas fa-save"/>
                <x-adminlte-button theme="secondary" label="Batal" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

@endsection
