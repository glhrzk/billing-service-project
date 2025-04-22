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
