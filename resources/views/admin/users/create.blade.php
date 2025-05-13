@extends('layouts.app')
@section('subtitle', 'Detail Pengguna')

@section('content_body')
    <x-adminlte-card title="Detail Pengguna" theme="dark" icon="fas fa-user">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <x-adminlte-input name="name" label="Nama" value="{{ old('name') }}" required/>

            <x-adminlte-input name="email" label="Email" type="email" value="{{ old('email') }}" required/>

            <x-adminlte-input name="phone" label="No. Telepon" value="{{ old('phone') }}"/>

            <x-adminlte-input name="address" label="Alamat" value="{{ old('address') }}"/>

            <x-adminlte-input name="due_date" label="Tanggal Jatuh Tempo" type="number" value="{{ old('due_date') }}"
                              min="1" max="31"/>

            <x-adminlte-input name="password" label="Password" type="password" required/>
            <x-adminlte-input name="password_confirmation" label="Konfirmasi Password" type="password" required/>

            <x-adminlte-button label="Submit" theme="success" type="submit" icon="fas fa-paper-plane"/>
            <x-adminlte-button label="Reset" theme="secondary" type="reset" icon="fas fa-undo" class="ml-2"/>
        </form>
    </x-adminlte-card>
@stop
