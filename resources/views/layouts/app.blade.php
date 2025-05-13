@extends('adminlte::page')
@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif
@stop

@section('content_header')
    <span></span>
@stop

@section('content')
    <x-adminlte-card
        theme-mode="outline" class="mt-sm-2 elevation-3"
        body-class="bg-light"
        header-class="bg-light" footer-class="bg-light border-top rounded border-light">
        @yield('content_body')
    </x-adminlte-card>
@stop

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop

@if ($errors->any())
    @push('js')
        <script>
            @foreach ($errors->all() as $error)
            toastr.error("{{ $error }}", "Error", {timeOut: 5000});
            @endforeach
        </script>
    @endpush
@endif

@push('js')
    <script>
        @if(session('success'))
        toastr.success('{{ session('success') }}', 'Success');
        @elseif(session('warning'))
        toastr.warning('{{ session('warning') }}', 'Warning');
        @elseif(session('info'))
        toastr.info('{{ session('info') }}', 'Info');
        @elseif(session('error'))
        toastr.error('{{ session('error') }}', 'Error');
        @endif
    </script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
    <style type="text/css">
        .small-break {
            /*line-height: 0.2*/
            height: 0.2rem;
        }

    </style>
@endpush
