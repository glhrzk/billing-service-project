@extends('layouts.app')
@section('subtitle', 'Paket Aktif Saat Ini')
@section('plugins.Datatables', true)

@section('content_body')
    @php
        $heads = [
            ['label' => '#', 'width' => 1, 'sortable' => false],
            'Nama Paket',
            'Kecepatan',
            'Harga',
            'Dibuat',
            ['label' => 'Aksi', 'center' => true, 'sortable' => false],
        ];

        $config = [
            'data' => $userPackages->map(function ($subscription, $index) {
                $btnDetail = '<button class="btn btn-xs btn-info" data-toggle="modal" title="Detail" data-target="#modalDetailSubscription-'.$subscription->id.'"><i class="fas fa-info-circle"></i></button>';

                return [
                    $index + 1,
                    $subscription->package_name_snapshot,
                    $subscription->package_speed_snapshot,
                    rupiah_label($subscription->package_price_snapshot),
                    $subscription->created_at->translatedFormat('d M Y'),
                    $btnDetail,
                ];
            })->toArray(),
            'order' => [[0, 'asc']],
        ];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" :config="$config"
                          striped hoverable bordered compressed beautify/>

    @foreach($userPackages as $subscription)
        <x-adminlte-modal id="modalDetailSubscription-{{ $subscription->id }}" title="Detail Langganan"
                          theme="info" size="lg" icon="fas fa-info-circle">
            <dl class="row mb-0">
                <dt class="col-sm-4">Nama Paket</dt>
                <dd class="col-sm-8">{{ $subscription->package_name_snapshot }}</dd>

                <dt class="col-sm-4">Kecepatan</dt>
                <dd class="col-sm-8">{{ $subscription->package_speed_snapshot }}</dd>

                <dt class="col-sm-4">Harga</dt>
                <dd class="col-sm-8">{{ rupiah_label($subscription->package_price_snapshot) }}</dd>

                <dt class="col-sm-4">Deskripsi</dt>
                <dd class="col-sm-8">{{ $subscription->package_description_snapshot }}</dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">
                    <span class="badge badge-{{ $subscription->is_active === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($subscription->is_active) }}
                    </span>
                </dd>

                @if($subscription->active_discount_amount > 0)
                    <dt class="col-sm-4">Diskon Awal</dt>
                    <dd class="col-sm-8">{{ rupiah_label($subscription->active_discount_amount) }}</dd>

                    <dt class="col-sm-4">Alasan Diskon</dt>
                    <dd class="col-sm-8">{{ $subscription->active_discount_reason }}</dd>

                    <dt class="col-sm-4">Durasi Diskon</dt>
                    <dd class="col-sm-8">{{ $subscription->active_discount_duration }} bulan</dd>
                @endif

                <dt class="col-sm-4">Dibuat Pada</dt>
                <dd class="col-sm-8">{{ $subscription->created_at->translatedFormat('d M Y H:i') }}</dd>
            </dl>
        </x-adminlte-modal>
    @endforeach
@stop
