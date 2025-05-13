@extends('layouts.app')
@section('subtitle', 'Welcome')
@section('plugins.Datatables', true)

@section('content_body')
    <form method="GET" action="{{ route('user.bill.history') }}">
        @csrf
        <div class="form-group row">
            <label for="year" class="col-sm-2 col-form-label font-weight-bold">Filter Tahun</label>
            <div class="col-sm-4">
                <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                    <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ (string) $selectedYear === (string) $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>


    @foreach($userBills as $userBill)
        <x-adminlte-card title="Tagihan Bulan: {{ month_label($userBill->billing_month) }}" theme="dark"
                         icon="fas fa-file-invoice">

            {{-- STATUS DAN TANGGAL --}}
            <div class="mb-3">
                <span
                    class="badge {{payment_status_badge($userBill->status)}}">{{ payment_status_label($userBill->status) }}</span>
                <span class="ml-1"><strong>Jatuh Tempo:</strong> Tanggal {{ $user->due_date }}</span>
            </div>

            {{-- TABEL PAKET --}}
            <table class="table table-bordered table-hover">
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
                @foreach($packageBillsGroup[$userBill->id] as $packageBill)
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

            {{-- TOTAL TAGIHAN --}}
            <div class="mt-3">
                <h5><strong>Total Tagihan:</strong> {{ rupiah_label($userBill->amount) }}</h5>
            </div>

            {{-- FOOTER BUTTONS --}}
            <x-slot name="footerSlot">

                {{-- Tombol Invoice --}}
                <a href="{{ route('user.invoice.show', $userBill->id) }}">
                    <x-adminlte-button class="align-items-center ml-2" theme="outline-secondary"
                                       label="Lihat Invoice"
                                       icon="fas fa-file-invoice" icon-class="mr-2"/>
                </a>

            </x-slot>

        </x-adminlte-card>

    @endforeach

@stop
