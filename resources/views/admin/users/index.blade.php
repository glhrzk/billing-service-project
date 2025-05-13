@extends('layouts.app')
@section('subtitle', 'Welcome')
@section('plugins.Datatables', true)

@section('content_body')
    @php
        $heads = [
            ['label' => '#', 'width' => 1, 'sortable' => false] ,
            'Nama',
            'Email',
            'Telepon',
            'Status',
            ['label' => 'Jatuh Tempo'],
            ['label' => 'Aksi', 'center' => true, 'sortable' => false],
        ];


        $config = [
            'data' => $users->map(function ($users, $index) {
                    $btnEdit = '<a href="'.route('admin.users.show', $users->id).'" class="btn btn-xs btn-default" title="Edit"><i class="fas fa-edit"></i></a>';
                    $status = '<span class="'. status_badge($users->status). ' rounded-pill px-2 ">  '. status_label($users->status) . ' </span>';

                return [
                    $index + 1,
                    $users->name,
                    $users->email,
                    $users->phone,
                    $status,
                    $users->due_date,
                    $btnEdit,
                ];
            })->toArray(),
            'order' => [[0, 'asc']],

        ];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" :config="$config"
                          striped hoverable bordered compressed beautify/>
@stop
