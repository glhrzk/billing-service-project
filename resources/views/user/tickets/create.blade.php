@extends('layouts.app')
@section('subtitle', 'Kirim Tiket Bantuan')

@section('content_body')
    <form action="{{ route('user.ticket.store') }}" method="POST">
        @csrf

        {{-- Judul Tiket --}}
        <x-adminlte-input name="subject" label="Subjek Keluhan"
                          placeholder="Misal: Internet lambat di pagi hari"
                          required="required">
            <x-slot name="prependSlot">
                <div class="input-group-text bg-secondary">
                    <i class="fas fa-info-circle"></i>
                </div>
            </x-slot>
        </x-adminlte-input>

        {{-- Pesan --}}
        <x-adminlte-textarea name="message" label="Deskripsi Keluhan"
                             placeholder="Jelaskan detail permasalahan yang Anda alami..."
                             rows=5 required="required">
            <x-slot name="prependSlot">
                <div class="input-group-text bg-secondary">
                    <i class="fas fa-align-left"></i>
                </div>
            </x-slot>
        </x-adminlte-textarea>

        {{-- Tombol --}}
        <div class="d-flex justify-content-between mt-3">
            <x-adminlte-button label="Beranda" theme="primary"
                               onclick="window.location.href='{{ route('user.dashboard') }}'"/>
            <x-adminlte-button type="submit" label="Kirim Tiket" theme="success"/>
        </div>
    </form>
@stop
