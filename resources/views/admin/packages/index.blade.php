@extends('layouts.app')
@section('subtitle', 'Daftar Paket')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Daftar Paket" theme="dark" icon="fas fa-box">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Kecepatan</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @foreach($packages as $package)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->speed }}</td>
                    <td>{{ rupiah_label($package->price) }}</td>
                    <td><span
                            class="{{ status_badge($package->status) }} rounded-pill px-2">  {{ status_label($package->status) }} </span>
                    </td>
                    <td align="center">
                        <a href="{{ route('admin.packages.show', $package->id) }}">
                            <x-adminlte-button theme="warning" icon="fas fa-edit" label="Edit"/>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-adminlte-card>
@stop
