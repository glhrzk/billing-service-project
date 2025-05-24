@extends('layouts.app')
@section('subtitle', 'Detail Tiket')

@push('css')
    <style>
        .chat-bubble {
            background-color: #f1f1f1; /* putih keabu-abuan */
            color: #212529;
            padding: 1rem;
            border-radius: 0.5rem;
            max-width: 70%;
        }

        .chat-bubble-user {
            margin-left: auto;
            text-align: right;
            background-color: #f1f1f1;
            color: #212529;
        }

        .chat-bubble-admin {
            margin-right: auto;
        }
    </style>
@endpush

@section('content_body')

    <x-adminlte-card title="Subjek: {{ $ticket->subject }}" theme="dark" icon="fas fa-ticket-alt">

        <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
            <div>
        <span class="badge {{ ticket_status_badge($ticket->status) }}">
            {{ ticket_status_label($ticket->status) }}
        </span>
                <small class="text-muted ml-2">
                    Dibuat: {{ $ticket->created_at->translatedFormat('d F Y H:i') }}
                </small>
            </div>
            <div class="bg-secondary text-white rounded px-3 py-2 mt-2 mt-md-0 ml-md-3" style="max-width: 60%;">
                <small><strong>Pesan Awal:</strong></small><br>
                {{ $ticket->description }}
            </div>
        </div>


        <hr style="border-top:1px solid #343a40;">

        {{-- CHAT BUBBLE --}}
        <div class="mb-4" style="max-height: 400px; overflow-y: auto;">
            @foreach($ticket->replies as $reply)
                <div class="mb-3">
                    <div class="chat-bubble
                    {{ $reply->user_id === auth()->id() ? 'chat-bubble-user' : 'chat-bubble-admin' }}"
                         style="max-width: 70%;" style="background-color: #f1eae9;">
                        <small><strong>{{ $reply->user->name }}</strong></small><br>
                        {{ $reply->message }}
                        <br>
                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FORM BALAS --}}
        <form method="POST" action="{{ route('user.ticket.reply.store', $ticket->id) }}">
            @csrf
            <div class="form-group">
                <label for="message">Balasan Anda:</label>
                <textarea name="message" class="form-control" rows="3" required
                          @if($ticket->status === 'closed') disabled @endif> @if($ticket->status === 'closed')
                        Ticket ditutup silahkan buat tiket baru
                    @endif </textarea>
            </div>
            <x-adminlte-button type="submit" theme="primary" label="Kirim Balasan" icon="fas fa-paper-plane"/>
            <a href="{{ route('user.ticket.index') }}">
                <x-adminlte-button theme="secondary" label="Kembali" icon="fas fa-arrow-left" class="float-right"/>
            </a>
        </form>

    </x-adminlte-card>

@endsection
