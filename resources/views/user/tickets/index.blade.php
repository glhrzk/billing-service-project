@extends('layouts.app')
@section('subtitle', 'Tiket Bantuan')

@section('content_body')

    <x-adminlte-card title="Daftar Tiket Bantuan" theme="dark" icon="fas fa-life-ring">

        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subjek</th>
                    <th>Status</th>
                    <th>Dibuat Pada</th>
                    <th>Terakhir di update</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>#{{ $ticket->id }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>
                            <span class="badge {{ ticket_status_badge($ticket->status) }}">
                                {{ ticket_status_label($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->translatedFormat('d F Y H:i') }}</td>
                        <td>
                            @if($ticket->replies->isNotEmpty())
                                {{ $ticket->replies->last()->created_at->translatedFormat('d F Y H:i') }}
                            @else
                                {{ $ticket->updated_at->translatedFormat('d F Y H:i') }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('user.ticket.reply.show', $ticket->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye mr-1"></i>Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada tiket bantuan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </x-adminlte-card>

@endsection
