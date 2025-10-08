@extends('layouts.app')
@section('subtitle', 'Langganan User')
@section('plugins.Datatables', true)

@section('content_body')

    @php
        $heads = [
            ['label' => '#', 'width' => 1, 'sortable' => false] ,
            'User',
            'Paket',
            'Dibuat',
            ['label' => 'Aksi', 'center' => true, 'sortable' => false],
        ];


        $config = [
            'data' => $userPackages->map(function ($userPackages, $index) {
                    $btnDeactivate = '<form method="POST" action="'.route('admin.user-package.deactivate', $userPackages->id).'"
                                 onsubmit="return confirm(\'Yakin ingin menghapus langganan ini?\')">
                                 '.csrf_field().'
                                 <input type="hidden" name="_method" value="PUT">
                                 <button type="submit" class="btn btn-xs btn-danger" title="Nonaktifkan">
                                    <i class="fas fa-power-off"></i>
                                 </button>
                              </form>';
                return [
                    $index + 1,
                    $userPackages->user->name,
                    $userPackages->package->name,
                    $userPackages->created_at->translatedFormat('d M Y'),
                    $btnDeactivate,
                ];
            })->toArray(),
            'order' => [[0, 'asc']],

        ];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" :config="$config"
                          striped hoverable bordered compressed beautify>
        <x-slot name="buttonsSlot">
            <x-adminlte-button label="Tambah Langganan" data-toggle="modal" data-target="#modalAddSubscription"
                               theme="primary" icon="fas fa-plus"/>
        </x-slot>
    </x-adminlte-datatable>

    {{-- MODAL TAMBAH LANGGANAN --}}
    <form action="{{ route('admin.user-package.store') }}" method="POST">

        <x-adminlte-modal id="modalAddSubscription" title="Tambah Langganan Baru" theme="primary" size="xl">
            @csrf
            <div class="form-group">
                <label for="user_id">Pilih User</label>
                <select class="form-control" name="user_id" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <label for="package_id" class="mt-3 mb-2">Pilih Paket:</label>
            <table class="table table-bordered table-hover" id="table-package-select">
                <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Nama Paket</th>
                    <th>Kecepatan</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($packages as $package)
                    <tr>
                        <td class="text-center">
                            <input type="radio" name="package_id" value="{{ $package->id }}"
                                   data-price="{{ $package->price }}" required>
                        </td>
                        <td>{{ $package->name }}</td>
                        <td>{{ $package->speed }}</td>
                        <td>{{ rupiah_label($package->price) }}</td>
                        <td>{{ $package->description }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="form-group mt-4">
                <label for="discount_amount">Diskon (Rp)</label>
                <input type="number" class="form-control" name="discount_amount" id="discount_amount" value="0" min="0">
            </div>

            <div class="form-group">
                <label for="discount_reason">Alasan Diskon</label>
                <input type="text" class="form-control" name="discount_reason"
                       placeholder="Opsional (misal: promo awal)">
            </div>

            <div class="form-group">
                <label for="final_amount">Total Setelah Diskon</label>
                <input type="text" class="form-control" id="final_amount" readonly>
            </div>

            <x-slot name="footerSlot">
                <x-adminlte-button theme="secondary" label="Batal" data-dismiss="modal"/>
                <x-adminlte-button theme="success" type="submit" label="Simpan"/>
            </x-slot>
        </x-adminlte-modal>
    </form>
@stop

@push('js')
    <script>
        function rupiah(value) {
            return new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR'}).format(value);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[name="package_id"]');
            const discountInput = document.getElementById('discount_amount');
            const finalAmountDisplay = document.getElementById('final_amount');

            function updateFinal() {
                let price = 0;
                radios.forEach(radio => {
                    if (radio.checked) price = parseInt(radio.dataset.price || 0);
                });
                const discount = parseInt(discountInput.value || 0);
                const total = Math.max(0, price - discount);
                finalAmountDisplay.value = rupiah(total);
            }

            radios.forEach(radio => radio.addEventListener('change', updateFinal));
            discountInput.addEventListener('input', updateFinal);
        });
    </script>
@endpush


