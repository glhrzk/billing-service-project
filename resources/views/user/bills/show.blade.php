@extends('layouts.app')
@section('subtitle', 'Welcome')
@section('plugins.Datatables', true)

@section('content_body')

    @if($userBills->isEmpty())
        {{-- Jika tidak ada tagihan --}}
        <x-adminlte-alert theme="secondary" title="Tidak ada tagihan">
            Anda tidak memiliki tagihan yang perlu dibayar saat ini.
            Terima kasih.
        </x-adminlte-alert>
    @endif

    @foreach($userBills as $userBill)
        <x-adminlte-card title="Tagihan Bulan: {{ month_label($userBill->billing_month) }}" theme="dark"
                         icon="fas fa-file-invoice">

            {{-- STATUS DAN TANGGAL --}}
            <div class="mb-3">
                <span
                    class="badge {{badge_status_label($userBill->status)}}">{{ payment_status_label($userBill->status) }}</span>
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
                {{-- Tombol Bayar --}}
                <x-adminlte-button class="align-items-center" theme="outline-primary"
                                   label="Bayar Sekarang"
                                   icon="fas fa-wallet" icon-class="mr-2"
                                   data-toggle="modal" data-target="#modalPayment-{{ $userBill->id }}"/>

                {{-- Tombol Invoice --}}
                <a href="{{ route('user.invoice.show', $userBill->id) }}">
                    <x-adminlte-button class="align-items-center ml-2" theme="outline-secondary"
                                       label="Lihat Invoice"
                                       icon="fas fa-file-invoice" icon-class="mr-2"/>
                </a>

            </x-slot>

        </x-adminlte-card>

        {{-- MODAL BAYAR --}}
        <form action="{{ route('user.bill.pay', $userBill->id) }}" method="POST" enctype="multipart/form-data">
            <x-adminlte-modal id="modalPayment-{{ $userBill->id }}"
                              title="Konfirmasi Pembayaran | {{ month_label($userBill->billing_month) }}"
                              theme="primary"
                              size="lg">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_bill_id" value="{{ $userBill->id }}">

                @php
                    $isDisabled = in_array($userBill->status, ['pending', 'paid']);
                @endphp

                {{-- Metode Pembayaran --}}
                <div class="form-group">
                    <label for="payment_method">Metode Pembayaran</label>
                    <select id="payment_method_{{ $userBill->id }}" class="form-control" name="payment_method"
                            required {{ $isDisabled ? 'disabled' : '' }}>
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash" {{ $userBill->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option
                            value="bank_transfer" {{ $userBill->payment_method == 'bank_transfer' ? 'selected' : '' }}>
                            Bank Transfer
                        </option>
                    </select>
                </div>

                {{-- Section Tanggal Transfer --}}
                <div id="transfer-section-{{ $userBill->id }}" class="d-none">
                    <div class="form-group">
                        <label for="transfer_date">Tanggal Transfer</label>
                        <input type="date" class="form-control" name="transfer_date"
                               value="{{ $userBill->transfer_date }}" {{ $isDisabled ? 'readonly' : '' }}>
                    </div>

                    <div class="form-group">
                        <label for="transfer_proof">Bukti Transfer</label><br>

                        @if($userBill->transfer_proof && $userBill->status != 'rejected')
                            {{-- Tampilkan Gambar --}}
                            {{-- Tampilkan Preview Gambar --}}
                            <img src="{{ asset('storage/' . $userBill->transfer_proof) }}" class="img-fluid mb-2"
                                 style="max-height: 300px;" alt="Bukti Transfer">
                        @endif

                        @if(!$isDisabled)
                            <input type="file" class="form-control mt-2"
                                   name="transfer_proof" {{ $isDisabled ? 'disabled' : '' }}>
                        @endif
                    </div>
                </div>

                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" type="button" label="Batal" data-dismiss="modal"/>

                    @if($isDisabled)
                        <x-adminlte-button theme="secondary" type="button" label="Sedang Diproses" disabled/>
                    @else
                        <x-adminlte-button theme="success" type="submit" label="Konfirmasi Bayar"/>
                    @endif
                </x-slot>
            </x-adminlte-modal>
        </form>

    @endforeach

@stop

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($userBills as $userBill)
            const paymentMethod{{ $userBill->id }} = document.getElementById('payment_method_{{ $userBill->id }}');
            const transferSection{{ $userBill->id }} = document.getElementById('transfer-section-{{ $userBill->id }}');

            paymentMethod{{ $userBill->id }}.addEventListener('change', function () {
                if (this.value === 'bank_transfer') {
                    transferSection{{ $userBill->id }}.classList.remove('d-none');
                } else {
                    transferSection{{ $userBill->id }}.classList.add('d-none');
                }
            });

            // Init State ketika modal dibuka
            if (paymentMethod{{ $userBill->id }}.value === 'bank_transfer') {
                transferSection{{ $userBill->id }}.classList.remove('d-none');
            }
            @endforeach
        });
    </script>
@endpush
