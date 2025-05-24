@extends('layouts.app')
@section('subtitle', 'Detail Tagihan Saya')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Detail Tagihan" theme="info" icon="fas fa-file-invoice">
        <div class="row">
            <div class="col-md-6">
                <x-adminlte-input name="billing_month" label="Bulan Tagihan"
                                  value="{{ month_label($userBill->billing_month) }}" disabled/>
            </div>
            <div class="col-md-6">
                <x-adminlte-input name="status" label="Status Pembayaran"
                                  value="{{ strtoupper(payment_status_label($userBill->status)) }}"
                                  igroup-size="sm" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text {{ payment_status_badge($userBill->status) }}">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>

            @if($userBill->discount_amount > 0)
                <div class="col-md-6">
                    <x-adminlte-input name="amount" label="Total Tagihan Sebelum Diskon"
                                      value="{{ rupiah_label($userBill->amount) }}" disabled/>
                </div>
            @else
                <div class="col-md-6">
                    <x-adminlte-input name="final_amount" label="Total Yang Harus Dibayar"
                                      value="{{ rupiah_label($userBill->final_amount) }}" disabled/>
                </div>
            @endif

            @if($userBill->discount_amount > 0)
                <div class="col-md-6">
                    <x-adminlte-input name="discount" label="Diskon (Rp)"
                                      value="{{ rupiah_label($userBill->discount_amount) }}" disabled/>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="final_amount" label="Total yang Harus Dibayar"
                                      value="{{ rupiah_label($userBill->final_amount) }}" disabled/>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="discount_reason" label="Alasan Diskon"
                                      value="{{ $userBill->discount_reason ?? '-' }}" disabled/>
                </div>
            @endif

            @if($userBill->status === 'paid')
                <div class="col-md-6">
                    <x-adminlte-input name="paid_at" label="Tanggal Lunas"
                                      value="{{ $userBill->updated_at->translatedFormat('l, j F Y H:i') }}" disabled/>
                </div>
            @endif
        </div>
    </x-adminlte-card>

    <x-adminlte-card title="Rincian Paket dalam Tagihan" theme="dark" icon="fas fa-box">
        @php
            $heads = ['#', 'Paket', 'Harga', 'Diskon', 'Harga Akhir', 'Alasan Diskon'];
            $config = [
                'data' => collect($userBill->billItems)->map(function ($item, $i) {
                    return [
                        $i + 1,
                        $item->billed_package_name,
                        rupiah_label($item->billed_package_price),
                        $item->package_discount_amount ? rupiah_label($item->package_discount_amount) : '-',
                        rupiah_label($item->final_amount),
                        $item->package_discount_reason ?? '-',
                    ];
                })->toArray(),
                'order' => [[0, 'asc']],
                'columns' => [['orderable' => false], null, null, null, null, null],
            ];
        @endphp

        <x-adminlte-datatable id="tableDetailUser" :heads="$heads" :config="$config" striped hoverable bordered
                              compressed beautify/>
    </x-adminlte-card>

    <x-adminlte-card title="Aksi" theme="light" icon="fas fa-cogs">
        <a href="{{ route('user.bills.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tagihan
        </a>

        <x-adminlte-button label="Lihat & Download Invoice"
                           icon="fas fa-file-invoice"
                           theme="success"
                           data-toggle="modal"
                           data-target="#modalInvoicePreview"/>

        @if($userBill->status === 'unpaid' || $userBill->status === 'rejected')
            <x-adminlte-button label="Konfirmasi Pembayaran"
                               icon="fas fa-wallet"
                               theme="primary"
                               data-toggle="modal"
                               data-target="#modalPaymentConfirm" class="ml-2"/>
        @endif
    </x-adminlte-card>

    {{-- Modal Preview Invoice --}}
    <x-adminlte-modal id="modalInvoicePreview" title="Preview Invoice" size="xl" theme="info" icon="fas fa-eye">
        <div style="max-height: 70vh; overflow-y: auto; background-color: white; padding: 10px;">
            @include('pdf.preview', ['userBill' => $userBill, 'fromModal' => true])
        </div>
        <x-slot name="footerSlot">
            <a href="{{ route('user.invoice.download', $userBill->id) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <x-adminlte-button theme="secondary" label="Tutup" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>

    {{-- Modal Konfirmasi Pembayaran --}}
    <form action="{{ route('user.bill.pay', $userBill->id) }}" method="POST" enctype="multipart/form-data">
        <x-adminlte-modal id="modalPaymentConfirm" title="Konfirmasi Pembayaran" theme="primary"
                          icon="fas fa-credit-card" size="lg">
            @csrf
            @method('PUT')

            <input type="hidden" name="user_bill_id" value="{{ $userBill->id }}">

            <div class="form-group">
                <label for="payment_method">Metode Pembayaran</label>
                <select name="payment_method" class="form-control" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="cash" {{ $userBill->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ $userBill->payment_method == 'bank_transfer' ? 'selected' : '' }}>
                        Bank Transfer
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="transfer_date">Tanggal Transfer</label>
                <input type="date" class="form-control" name="transfer_date" value="{{ $userBill->transfer_date }}">
            </div>

            <div class="form-group">
                <label for="transfer_proof">Bukti Transfer</label>
                @if($userBill->transfer_proof)
                    <img src="{{ asset('storage/' . $userBill->transfer_proof) }}" class="img-fluid mb-2"
                         style="max-height: 300px;" alt="Bukti Transfer">
                @endif
                <input type="file" class="form-control mt-2" name="transfer_proof">
            </div>

            <x-slot name="footerSlot">
                <x-adminlte-button theme="secondary" label="Batal" data-dismiss="modal"/>
                <x-adminlte-button theme="success" type="submit" label="Kirim Konfirmasi"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
@endsection
