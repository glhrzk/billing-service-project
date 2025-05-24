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
                <x-adminlte-input name="status" label="Status Pembayaran"
                                  value="{{ strtoupper(payment_status_label($userBill->status)) }}"
                                  igroup-size="sm" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text {{ payment_status_badge($userBill->status)  }}">
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
                    <x-adminlte-input name="final_amount" label="Yang Harus Dibayar"
                                      value="{{ rupiah_label($userBill->final_amount) }}" disabled/>
                </div>
            @endif


            @if($userBill->status === 'paid')
                <div class="col-md-3">
                    <x-adminlte-input name="amount" label="Tanggal Lunas"
                                      value="{{ $userBill->updated_at->translatedFormat('l, j F Y H:i') }}"
                                      disabled/>
                </div>
            @endif

            @if($userBill->discount_amount > 0)

                <div class="col-md-3">
                    <x-adminlte-input name="total_discount" label="Diskon (Rp)"
                                      value="{{ $userBill->discount_amount > 0 ? rupiah_label($userBill->discount_amount) : '-' }}"
                                      disabled/>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="final_amount" label="Total yang Harus Dibayar"
                                      value="{{ rupiah_label($userBill->final_amount) }}" disabled/>
                </div>
                <div class="col-md-6">
                    <x-adminlte-input name="package_discount_reason" label="Alasan Diskon"
                                      value="{{ $userBill->discount_reason ?? '-' }}" disabled/>
                </div>
            @endif



            @if($userBill->status === 'unpaid')
                <x-slot name="footerSlot">
                    <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#modalEditDiskon">
                        <i class="fas fa-edit"></i> Tambah/Edit Diskon Insidental
                    </button>
                </x-slot>
            @endif
        </div>
    </x-adminlte-card>

    <x-adminlte-card title="Rincian Paket dalam Tagihan" theme="dark" icon="fas fa-box">
        @php
            $heads = ['#', 'Paket', 'Harga', 'Diskon', 'Harga Akhir', 'Alasan Diskon'];
            $config = [
                'data' => collect($userBill->billItems)->map(function ($billItems, $i) {
                    return [
                        $i + 1,
                        $billItems->billed_package_name,
                        rupiah_label($billItems->billed_package_price),
                        $billItems->package_discount_amount ? rupiah_label($billItems->package_discount_amount) : '-',
                        rupiah_label($billItems->final_amount),
                        $billItems->package_discount_reason ?? '-',
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

        <x-adminlte-button label="Lihat & Download Invoice"
                           icon="fas fa-file-invoice"
                           theme="success"
                           data-toggle="modal"
                           data-target="#modalInvoicePreview"/>

    </x-adminlte-card>

    <x-adminlte-modal id="modalInvoicePreview" title="Preview Invoice"
                      size="xl" theme="info" icon="fas fa-eye">

        <div style="max-height: 70vh; overflow-y: auto; background-color: white; padding: 10px;">
            @include('pdf.preview', ['userBill' => $userBill, 'fromModal' => true])
        </div>

        <x-slot name="footerSlot">
            <a href="{{ route('admin.invoice.download', $userBill->id) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <x-adminlte-button theme="secondary" label="Tutup" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>


    <form action="{{ route('admin.bills.update', $userBill->id) }}" method="POST">
        <x-adminlte-modal id="modalEditDiskon" title="Edit Diskon Insidental" theme="warning" icon="fas fa-pen"
                          size="lg">
            @csrf
            @method('PUT')

            <x-adminlte-input name="discount_amount" label="Nominal Diskon (Rp)"
                              type="number" value="{{ old('discount_amount', $userBill->discount_amount) }}"
                              placeholder="Contoh: 15000"/>

            <x-adminlte-input name="discount_reason" label="Alasan Diskon"
                              value="{{ old('discount_reason', $userBill->discount_reason) }}"
                              placeholder="Contoh: Gangguan internet, kompensasi, dll"/>

            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="secondary" label="Batal" data-dismiss="modal"/>
                <x-adminlte-button type="submit" theme="success" label="Simpan"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

@endsection
