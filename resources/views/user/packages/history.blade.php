@extends('layouts.app')
@section('subtitle', 'Welcome')
@section('plugins.Datatables', true)

@section('content_body')
    @php
        $heads = [
            ['label' => 'No', 'width' => 1, 'sortable' => false] ,
            'Nama Paket',
            'Kecepatan',
            'Harga',
            'Awal Berlangganan',
            'Deskripsi',
            'Status',
        ];

        $config = [
            'data' => $user->map(function ($user, $index) {
                return [
                    $index + 1,
                    $user->locked_name,
                    rupiah_label($user->locked_price),
                    $user->locked_speed,
                    $user->created_at->format('d-m-Y'),
                    $user->locked_description,
                    status_label($user->is_active),
                ];
            })->toArray(),
            'order' => [[0, 'asc']],

        ];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" :config="$config"
                          striped hoverable bordered compressed/>
@stop
