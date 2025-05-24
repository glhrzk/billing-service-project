<!-- resources/views/admin/tickets/index.blade.php -->
@extends('layouts.app')
@section('subtitle', 'Daftar Tiket Bantuan')

@section('content_body')
    <x-adminlte-card title="Daftar Tiket Bantuan Pengguna" theme="dark" icon="fas fa-headset">
        <form method="GET">
            <div class="col-md-3">
                <x-adminlte-select name="status" label="Status" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Semua --</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Baru</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang di Proses
                    </option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                </x-adminlte-select>
            </div>
        </form>
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pengguna</th>
                <th>Subjek</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Terakhir Update</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>#{{ $ticket->id }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>
                        <span class="badge {{ ticket_status_badge($ticket->status) }}">
                            {{ ticket_status_label($ticket->status) }}
                        </span>
                    </td>
                    <td>{{ $ticket->created_at->translatedFormat('l, d F Y H:i') }}</td>
                    <td>
                        @if($ticket->replies->isNotEmpty())
                            {{ $ticket->replies->last()->created_at->translatedFormat('l, d F Y H:i') }}
                        @else
                            {{ $ticket->updated_at->translatedFormat('l, d F Y H:i') }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.tickets.reply.show', $ticket->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada tiket masuk</td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </x-adminlte-card>
@endsection
