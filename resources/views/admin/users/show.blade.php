@extends('layouts.app')
@section('subtitle', 'Detail Pengguna')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-card title="Detail Pengguna" theme="dark" icon="fas fa-user">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <x-adminlte-input name="name" label="Nama" value="{{ $user->name }}" required/>

            <x-adminlte-input name="email" label="Email" type="email" value="{{ $user->email }}" required/>

            <x-adminlte-input name="phone" label="No. Telepon" value="{{ $user->phone }}"/>

            <x-adminlte-input name="address" label="Alamat" value="{{ $user->address }}"/>

            <x-adminlte-input name="due_date" label="Tanggal Jatuh Tempo" type="number" value="{{ $user->due_date }}"
                              min="1" max="31"/>

            <x-adminlte-select name="status" label="Status">
                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </x-adminlte-select>

            <x-adminlte-input name="password" label="Password" type="password"
                              placeholder="kosongkan jika tidak ada perubahan password"/>
            <x-adminlte-input name="password_confirmation" label="Konfirmasi Password" type="password"
                              placeholder="kosongkan jika tidak ada perubahan password"/>

            <x-adminlte-button label="Simpan Perubahan" theme="success" type="submit" icon="fas fa-save"/>
            <x-adminlte-button label="Kembali" theme="secondary"
                               onclick="window.location='{{ route('admin.users.index') }}'" type="button"
                               icon="fas fa-arrow-left" class="ml-2"/>
        </form>
    </x-adminlte-card>
@stop
