@extends('adminlte::page')

@section('title', 'Dashboard Admin')
@section('content_header')
    <h1>Dashboard Admin</h1>
@stop

@section('content')
    {{-- Statistik Ringkas --}}
    <div class="row">
        <div class="col-md-3 mb-3">
            <x-adminlte-small-box icon="fas fa-users" theme="info" url=" {{ route('admin.users.index') }} "
                                  url-text="Lihat pelanggan">
                <x-slot name="title">{{ $totalUsers }}</x-slot>
                Total Pelanggan
            </x-adminlte-small-box>
        </div>
        <div class="col-md-3 mb-3">
            <x-adminlte-small-box icon="fas fa-box" theme="primary" url=" {{ route('admin.packages.index') }}"
                                  url-text="Lihat paket">
                <x-slot name="title">{{ $totalPackages }}</x-slot>
                Paket Internet
            </x-adminlte-small-box>
        </div>
        <div class="col-md-3 mb-3">
            <x-adminlte-small-box icon="fas fa-wallet" theme="success" url=" {{ route('admin.bills.index') }} "
                                  url-text="Tagihan Bulan ini">
                <x-slot name="title">{{ rupiah_label($billingThisMonth) }}</x-slot>
                Tagihan Bulan Ini
            </x-adminlte-small-box>
        </div>
        <div class="col-md-3 mb-3">
            <x-adminlte-small-box icon="fas fa-check-circle" theme="teal" url="#" url-text="Pembayaran bulan ini">
                <x-slot name="title">{{ rupiah_label($paymentsThisMonth) }}</x-slot>
                Pembayaran Masuk
            </x-adminlte-small-box>
        </div>
    </div>

    <div class="row">
        {{-- Tiket Terbaru --}}
        <div class="col-md-6 mb-4">
            <x-adminlte-card title="Tiket Bantuan Terbaru" icon="fas fa-life-ring" class="h-100">
                <ul class="list-group list-group-flush">
                    @forelse($latestTickets as $ticket)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $ticket->subject }}</strong><br>
                                <small>{{ $ticket->user->name }} - {{ $ticket->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge badge-{{ $ticket->status === 'open' ? 'warning' : 'success' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center">Tidak ada tiket terbaru.</li>
                    @endforelse
                </ul>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-dark mt-3">Lihat Semua Tiket</a>
            </x-adminlte-card>
        </div>

        {{-- Pembayaran Menunggu Konfirmasi --}}
        <div class="col-md-6 mb-4">
            <x-adminlte-card title="Pembayaran Menunggu Konfirmasi" icon="fas fa-clock" class="h-100">
                <ul class="list-group list-group-flush">
                    @forelse($pendingBills as $bill)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $bill->user->name }}</strong><br>
                                <small>
                                    Nominal: {{ rupiah_label($bill->final_amount) }} -
                                    {{ $bill->transfer_date ? \Carbon\Carbon::parse($bill->transfer_date)->format('d M Y') : '-' }}
                                </small>

                            </div>
                            <span class="badge badge-warning">Pending</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center">Tidak ada pembayaran yang perlu dikonfirmasi.</li>
                    @endforelse
                </ul>
                <a href="{{ route('admin.bills.verification') }}" class="btn btn-sm btn-warning mt-3">Lihat Semua
                    Tagihan</a>
            </x-adminlte-card>
        </div>
    </div>
@stop
