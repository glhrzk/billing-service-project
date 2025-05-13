@extends('layouts.app')
@section('subtitle', 'Tambah Paket')

@section('content_body')
    <x-adminlte-card title="Tambah Paket" theme="dark">
        <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf

            <x-adminlte-input name="name" label="Nama Paket" value="{{ old('name') }}" required/>
            <x-adminlte-input name="speed" label="Kecepatan" placeholder="MBps" value="{{ old('speed') }}" required/>
            <x-adminlte-input name="price" label="Harga" type="number" min="0" value="{{ old('price') }}" required/>
            <x-adminlte-textarea name="description" label="Deskripsi" value="{{ old('description') }}" required/>
            <x-adminlte-select name="status" label="Status" required>
                <option value="active" @if(old('status') === 'active') selected @endif>Aktif</option>
                <option value="inactive" @if(old('status') === 'inactive') selected @endif>Nonaktif</option>
            </x-adminlte-select>

            <x-adminlte-button label="Submit" theme="success" type="submit" icon="fas fa-paper-plane"/>
            <x-adminlte-button label="Reset" theme="secondary" type="reset" icon="fas fa-undo" class="ml-2"/>
        </form>
    </x-adminlte-card>
@stop
