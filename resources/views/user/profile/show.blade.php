@extends('layouts.app')
@section('content_body')
    <x-adminlte-input name="name" placeholder="name" disabled value="{{ $user->name }}" label="Nama">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-user"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    <x-adminlte-input name="email" placeholder="email" disabled value="{{ $user->email }}" label="Email">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-envelope"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    <x-adminlte-input name="phone" placeholder="phone" disabled value="{{ $user->phone }}" label="No. Telepon">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-phone"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    <x-adminlte-input name="address" placeholder="address" disabled value="{{ $user->address }}" label="Alamat">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-address-card"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    <x-adminlte-input name="due_date" placeholder="due date" disabled value="{{ $user->due_date }}"
                      label="Tanggal Jatuh Tempo">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-calendar"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

    <hr class="my-3">
    <x-adminlte-button class="ml-auto" theme="primary" type="button" label="Beranda"
                       onclick="window.location.href='{{ route('user.dashboard') }}'"/>
    <x-adminlte-button class="ml-auto" theme="success" type="button" label="Kembali"
                       onclick="window.location.href='{{ url()->previous() }}'"/>

@stop
