@extends('layouts.app')
@section('subtitle', 'Dashboard Pengguna')

@section('content_body')
    <h3 class="mb-4">Halo, {{ auth()->user()->name }} 👋</h3>

    {{-- Statistik Ringkas --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            @php
                $totalUnpaid = $activeBill?->sum('final_amount') ?? 0;
            @endphp
            <x-adminlte-small-box
                title="{{ rupiah_label($totalUnpaid) }}"
                text="Total Tagihan Aktif"
                icon="fas fa-wallet" theme="warning"
                url="{{ route('user.bills.index') }}"
                url-text="Lihat Tagihan"/>
        </div>

        <div class="col-md-6 mb-3">
            @if($activePackage)
                <x-adminlte-small-box title="{{ $activePackage->package_name_snapshot }}"
                                      text="Paket Aktif: {{ rupiah_label($activePackage->package_price_snapshot) }}"
                                      icon="fas fa-wifi" theme="info"
                                      url="{{ route('user.package.index') }}"
                                      url-text="Lihat Langganan"/>
            @else
                <x-adminlte-small-box title="Tidak Ada Paket Aktif"
                                      text="Silakan hubungi admin."
                                      icon="fas fa-exclamation-triangle" theme="danger"/>
            @endif
        </div>
    </div>

    {{-- Ringkasan Riwayat Pembayaran dan Tiket --}}
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Riwayat Pembayaran Terakhir" icon="fas fa-money-check-alt" theme="light">
                <ul class="list-group list-group-flush">
                    @forelse($recentPayments as $bill)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ month_label($bill->billing_month) }}</strong><br>
                                <small>{{ ucfirst($bill->payment_method) }}
                                    - {{ rupiah_label($bill->final_amount) }}</small>
                            </div>
                            <span class="badge {{ payment_status_badge($bill->status) }}">
                                {{ payment_status_label($bill->status) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center">Belum ada riwayat pembayaran.</li>
                    @endforelse
                </ul>
                <a href="{{ route('user.bill.history') }}" class="btn btn-sm btn-primary mt-3">Lihat Semua</a>
            </x-adminlte-card>
        </div>

        <div class="col-md-6">
            <x-adminlte-card title="Tiket Bantuan Terakhir" icon="fas fa-life-ring" theme="light">
                <ul class="list-group list-group-flush">
                    @forelse($recentTickets as $ticket)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $ticket->subject }}</strong><br>
                                <small>{{ $ticket->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge {{ ticket_status_badge($ticket->status) }}">
                                {{ ticket_status_label($ticket->status) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center">Belum ada tiket bantuan.</li>
                    @endforelse
                </ul>
                <a href="{{ route('user.ticket.index') }}" class="btn btn-sm btn-primary mt-3">Lihat Semua Tiket</a>
            </x-adminlte-card>
        </div>
    </div>
@endsection
