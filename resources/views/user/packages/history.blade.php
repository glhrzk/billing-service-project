@extends('layouts.app')
@section('subtitle', 'Paket Aktif Saat Ini')
@section('plugins.Datatables', true)

@section('content_body')
    @php
        $heads = [
            ['label' => 'No', 'width' => 1, 'sortable' => false],
            'Nama Paket',
            'Kecepatan',
            'Harga',
            'Awal Berlangganan',
            'Berhenti',
            'Deskripsi',
            'Status',
        ];

        $config = [
            'data' => $userPackages->map(function ($subscription, $index) {
                return [
                    $index + 1,
                    $subscription->package_name_snapshot,
                    $subscription->package_speed_snapshot,
                    rupiah_label($subscription->package_price_snapshot),
                    $subscription->created_at->format('d-m-Y'),
                    $subscription->is_active === 'inactive' && $subscription->updated_at ? $subscription->updated_at->format('d-m-Y') : '-',
                    $subscription->package_description_snapshot,
                    '<span class="badge ' . status_badge($subscription->is_active) . '">' . status_label($subscription->is_active) . '</span>',
                ];
            })->toArray(),
            'order' => [[0, 'asc']],
        ];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" :config="$config"
                          striped hoverable bordered compressed/>
@stop
