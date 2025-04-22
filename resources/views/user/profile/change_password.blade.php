@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

    @include('notifications.alerts')
    <form action="{{ route('user.profile.password.update', auth()->id()) }}" method="POST">
        <x-adminlte-card title="Profile" theme-mode="outline" class="mt-sm-2 elevation-3" body-class="bg-light"
                         header-class="bg-light" footer-class="bg-light border-top rounded border-light" collapsible
                         removable>
            @csrf
            @method('PUT')

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

            <x-slot name="footerSlot">
                <x-adminlte-button class="ml-auto" theme="success" type="submit" label="Kirim"/>
                <x-adminlte-button class="ml-auto" theme="danger" type="reset" label="Reset"/>
            </x-slot>
        </x-adminlte-card>
    </form>

@stop
