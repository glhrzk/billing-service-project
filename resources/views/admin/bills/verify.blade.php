<!-- resources/views/admin/bills/verify.blade.php -->
@extends('adminlte::page')
@section('title', 'Verifikasi Pembayaran')
@section('content_header')
    <h1>Verifikasi Pembayaran</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Informasi Tagihan</h5>
            <table class="table table-bordered">
                <tr>
                    <th>Nama User</th>
                    <td>{{ $userBill->user->name }}</td>
                </tr>
                <tr>
                    <th>Bulan Tagihan</th>
                    <td>{{ \Carbon\Carbon::parse($userBill->billing_month)->translatedFormat('F Y') }}</td>
                </tr>
                <tr>
                    <th>Total Tagihan</th>
                    <td>{{ rupiah_label($userBill->amount) }}</td>
                </tr>
                <tr>
                    <th>Diskon</th>
                    <td>{{ $userBill->discount_amount ? rupiah_label($userBill->discount_amoint) : '-' }}</td>
                </tr>
                <tr>
                    <th>Alasan Diskon</th>
                    <td>{{ $userBill->discount_reason ? $userBill->discount_reason : '-' }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ ucfirst($userBill->payment_method) }}</td>
                </tr>
                <tr>
                    <th>Tanggal Transfer</th>
                    <td>{{ $userBill->transfer_date ? \Carbon\Carbon::parse($userBill->transfer_date)->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr>
                    <th>Bukti Transfer</th>
                    <td>
                        @if($userBill->transfer_proof)
                            <a href="{{ asset('storage/' . $userBill->transfer_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $userBill->transfer_proof) }}" width="250">
                            </a>
                        @else
                            <span class="text-muted">Tidak ada bukti transfer.</span>
                        @endif
                    </td>
                </tr>
            </table>

            <form action="{{ route('admin.bills.verify.action', $userBill->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mt-4">
                    <button name="action" value="approve" class="btn btn-success">
                        <i class="fas fa-check"></i> Konfirmasi Pembayaran
                    </button>
                    <button name="action" value="reject" class="btn btn-danger"
                            onclick="return confirm('Tolak pembayaran ini?')">
                        <i class="fas fa-times"></i> Tolak Pembayaran
                    </button>
                    <a href="{{ route('admin.bills.verification') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
@stop
