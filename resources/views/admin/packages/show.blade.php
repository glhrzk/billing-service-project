@extends('layouts.app')
@section('subtitle', 'Edit Paket')

@section('content_body')
    <x-adminlte-card title="Edit Paket" theme="dark">
        <form method="POST" action="{{ route('admin.packages.update', $package->id) }}">
            @csrf
            @method('PUT')

            <x-adminlte-input name="name" label="Nama Paket" value="{{ $package->name }}" required/>
            <x-adminlte-input name="speed" label="Kecepatan" value="{{ $package->speed }}" required/>
            <x-adminlte-input name="price" label="Harga" type="number" value="{{ $package->price }}" required/>
            <x-adminlte-textarea name="description" label="Deskripsi">{{ $package->description }}</x-adminlte-textarea>
            <x-adminlte-select name="status" label="Status">
                <option value="active" {{ $package->status == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $package->status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </x-adminlte-select>

            <x-adminlte-button theme="success" type="submit" label="Simpan Perubahan" icon="fas fa-save"/>
            <x-adminlte-button theme="secondary" type="button" label="Kembali"
                               onclick="window.location='{{ route('admin.packages.index') }}'" icon="fas fa-arrow-left"
                               class="ml-2"/>
        </form>
    </x-adminlte-card>
@stop
