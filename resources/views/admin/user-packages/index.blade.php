@extends('layouts.app')
@section('subtitle', 'Langganan User')
@section('plugins.Datatables', true)

@section('content_body')
    <x-adminlte-button class="mb-2 float-right" label="Tambah Langganan" data-toggle="modal"
                       data-target="#modalAddSubscription" theme="primary"/>

    @php
        $heads = [
            ['label' => '#', 'width' => 1, 'sortable' => false],
            'User',
            'Paket',
            'Kecepatan',
            'Harga',
            'Dibuat',
            ['label' => 'Aksi', 'center' => true, 'sortable' => false],
        ];

        $config = [
            'data' => $userPackages->map(function ($userPackages, $index) {
                $btnDetail = '<button class="btn btn-xs btn-info mr-1" data-toggle="modal" title="Detail" data-target="#modalDetailSubscription-'.$userPackages->id.'"><i class="fas fa-info-circle"></i></button>';
                $btnDeactivate = '<form method="POST" class="d-inline"  action="'.route('admin.user-package.deactivate', $userPackages->id).'" onsubmit="return confirm(\'Yakin ingin menghapus langganan ini?\')">'.csrf_field().'<input type="hidden" name="_method" value="PUT"><button type="submit" class="btn btn-xs btn-danger" title="Nonaktifkan"><i class="fas fa-power-off"></i></button></form>';


                return [
                    $index + 1,
                    $userPackages->user->name,
                    $userPackages->locked_name,
                    $userPackages->locked_speed,
                    rupiah_label($userPackages->locked_price),
                    $userPackages->created_at->translatedFormat('d M Y'),
                    $btnDetail . $btnDeactivate,
                ];
            })->toArray(),
            'order' => [[0, 'asc']],
        ];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" head-theme="dark" :config="$config"
                          striped hoverable bordered compressed beautify/>

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

            {{-- OPSI PROMO DISKON --}}
            <div class="form-group mt-4">
                <label for="has_discount">Promo Diskon?</label>
                <select class="form-control" id="has_discount" name="has_discount">
                    <option value="no" selected>Tidak</option>
                    <option value="yes">Ya</option>
                </select>
            </div>

            <div id="discount-section" class="d-none">
                <div class="form-group">
                    <label for="initial_discount_amount">Jumlah Diskon (Rp)</label>
                    <input type="number" class="form-control" name="initial_discount_amount"
                           id="initial_discount_amount" min="0">
                </div>

                <div class="form-group">
                    <label for="initial_discount_duration">Durasi Diskon (bulan)</label>
                    <input type="number" class="form-control" name="initial_discount_duration"
                           id="initial_discount_duration" min="1" value="1">
                </div>

                <div class="form-group">
                    <label for="initial_discount_reason">Alasan Promo</label>
                    <input type="text" class="form-control" name="initial_discount_reason"
                           placeholder="Contoh: Promo Idul Fitri">
                </div>
                <div class="form-group">
                    <label for="final_discounted_total">Total Setelah Diskon</label>
                    <input type="text" class="form-control" id="final_discounted_total" readonly>
                </div>
            </div>

            <x-slot name="footerSlot">
                <x-adminlte-button theme="secondary" label="Batal" data-dismiss="modal"/>
                <x-adminlte-button theme="success" type="submit" label="Simpan"/>
            </x-slot>
        </x-adminlte-modal>
    </form>

    @foreach($userPackages as $subscription)
        <x-adminlte-modal id="modalDetailSubscription-{{ $subscription->id }}" title="Detail Langganan"
                          theme="info" size="lg" icon="fas fa-info-circle">
            <dl class="row mb-0">
                <dt class="col-sm-3">User</dt>
                <dd class="col-sm-8">{{ $subscription->user->name }}</dd>

                <dt class="col-sm-3">Nama Paket</dt>
                <dd class="col-sm-8">{{ $subscription->locked_name }}</dd>

                <dt class="col-sm-3">Kecepatan</dt>
                <dd class="col-sm-8">{{ $subscription->locked_speed }}</dd>

                <dt class="col-sm-3">Harga</dt>
                <dd class="col-sm-8">{{ rupiah_label($subscription->locked_price) }}</dd>

                <dt class="col-sm-3">Deskripsi</dt>
                <dd class="col-sm-8">{{ $subscription->locked_description }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-8">
                <span class="badge badge-{{ $subscription->is_active === 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($subscription->is_active) }}
                </span>
                </dd>

                @if($subscription->initial_discount_amount > 0)
                    <dt class="col-sm-3">Diskon Awal</dt>
                    <dd class="col-sm-8">{{ rupiah_label($subscription->initial_discount_amount) }}</dd>

                    <dt class="col-sm-3">Alasan Diskon</dt>
                    <dd class="col-sm-8">{{ $subscription->initial_discount_reason }}</dd>

                    <dt class="col-sm-3">Durasi Diskon</dt>
                    <dd class="col-sm-8">{{ $subscription->initial_discount_duration }} bulan</dd>
                @endif

                <dt class="col-sm-3">Dibuat Pada</dt>
                <dd class="col-sm-8">{{ $subscription->created_at->translatedFormat('d M Y H:i') }}</dd>
            </dl>
        </x-adminlte-modal>
    @endforeach

@stop

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hasDiscount = document.getElementById('has_discount');
            const discountSection = document.getElementById('discount-section');
            const radios = document.querySelectorAll('input[name="package_id"]');
            const discountInput = document.getElementById('initial_discount_amount');
            const finalTotalDisplay = document.getElementById('final_discounted_total');

            function rupiah(value) {
                return new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR'}).format(value);
            }

            function updateFinalTotal() {
                let price = 0;
                radios.forEach(radio => {
                    if (radio.checked) {
                        price = parseInt(radio.dataset.price || 0);
                    }
                });

                const discount = parseInt(discountInput.value || 0);
                const final = Math.max(0, price - discount);

                finalTotalDisplay.value = rupiah(final);
            }

            hasDiscount.addEventListener('change', function () {
                if (this.value === 'yes') {
                    discountSection.classList.remove('d-none');
                    updateFinalTotal();
                } else {
                    discountSection.classList.add('d-none');
                    finalTotalDisplay.value = '';
                }
            });

            radios.forEach(radio => radio.addEventListener('change', updateFinalTotal));
            discountInput.addEventListener('input', updateFinalTotal);

            // Reset saat modal ditutup
            $('#modalAddSubscription').on('hidden.bs.modal', function () {
                hasDiscount.value = 'no';
                discountSection.classList.add('d-none');
                finalTotalDisplay.value = '';
            });
        });
    </script>
@endpush
