@extends('layouts.app')
@section('subtitle', 'Welcome')

@section('content_body')
    <form action="{{ route('admin.profile.password.update', auth()->id()) }}" method="POST">
        @csrf
        @method('PATCH')

        <x-adminlte-input name="current_password" placeholder="password saat ini" type="password" autocomplete="off"
                          label="Password saat ini"
                          required="yes">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-lock-open"></i>
                </div>
            </x-slot>
        </x-adminlte-input>

        <x-adminlte-input name="new_password" placeholder="password baru" type="password" autocomplete="off"
                          label="Password baru"
                          required="yes">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-lock"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
        <x-adminlte-input name="new_password_confirmation" placeholder="konfirmasi password baru" type="password"
                          label="Konfirmasi password baru"
                          autocomplete="off" required="yes">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-lock"></i>
                </div>
            </x-slot>
        </x-adminlte-input>

        <hr class="my-3">
        <x-adminlte-button class="ml-auto" theme="primary" type="button" label="Beranda"
                           onclick="window.location.href='{{ route('user.dashboard') }}'"/>
        <x-adminlte-button class="ml-auto" theme="success" type="submit" label="Simpan"/>
        <x-adminlte-button class="ml-auto" theme="danger" type="button" label="Kembali"
                           onclick="window.location.href='{{ url()->previous() }}'"/>
    </form>
@stop
