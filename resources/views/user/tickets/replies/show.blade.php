@extends('layouts.app')
@section('subtitle', 'Detail Tiket Saya')

@push('css')
    <style>
        .chat-bubble {
            background-color: #f1f1f1;
            color: #212529;
            padding: 1rem;
            border-radius: 0.5rem;
            max-width: 70%;
            word-break: break-word;
        }

        .chat-bubble-user {
            margin-left: auto;
            text-align: right;
            background-color: #d1e7dd;
        }

        .chat-bubble-admin {
            margin-right: auto;
            background-color: #f1f1f1;
        }

        .chat-image {
            max-width: 200px;
            height: auto;
            border-radius: 0.5rem;
        }
    </style>
@endpush

@section('content_body')
    <x-adminlte-card title="Subjek: {{ $ticket->subject }}" theme="dark" icon="fas fa-ticket-alt">
        <div class="d-flex justify-content-start align-items-start flex-wrap mb-3">
            <div>
                <span class="badge {{ ticket_status_badge($ticket->status) }}">
                    {{ ticket_status_label($ticket->status) }}
                </span>
                <small class="text-muted ml-2">
                    Dibuat: {{ $ticket->created_at->translatedFormat('d F Y H:i') }}
                </small>
            </div>
        </div>

        <hr style="border-top:1px solid #343a40;">

        <div class="mb-4" style="max-height: 400px; overflow-y: auto;">
            {{-- Pesan awal sebagai chat pertama --}}
            <div class="d-flex mb-3 justify-content-end">
                <div class="chat-bubble chat-bubble-user">
                    <small><strong>{{ $ticket->user->name }}</strong></small><br>
                    {{ $ticket->description }}
                    <div class="text-muted small mt-1 text-end">{{ $ticket->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Balasan berikutnya --}}
            @foreach($ticket->replies as $reply)
                @php
                    $isSenderUser = $reply->user_id === auth()->id();
                    $bubbleClass = $isSenderUser ? 'chat-bubble-user' : 'chat-bubble-admin';
                    $alignClass = $isSenderUser ? 'justify-content-end' : 'justify-content-start';
                @endphp

                <div class="d-flex mb-3 {{ $alignClass }}">
                    <div class="chat-bubble {{ $bubbleClass }}">
                        <small><strong>{{ $reply->user->name }}</strong></small><br>
                        {{ $reply->message }}

                        @if($reply->attachment)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $reply->attachment) }}"
                                     alt="Lampiran Gambar"
                                     class="chat-image">
                            </div>
                        @endif

                        <div class="text-muted small mt-1 text-end">{{ $reply->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($ticket->status != 'closed')
            <form method="POST" enctype="multipart/form-data"
                  action="{{ route('user.ticket.reply.store', $ticket->id) }}" id="replyTicketForm">
                @csrf
                <div class="form-group">
                    <label for="message">Balasan Anda:</label>
                    <textarea name="message" class="form-control" rows="3" required></textarea>

                    <label for="attachment">Lampiran Gambar (opsional):</label>
                    <input type="file" name="attachment" accept="image/*" class="form-control">
                </div>
            </form>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                @if($ticket->status != 'closed')
                    <x-adminlte-button type="submit" theme="primary" label="Kirim Balasan" icon="fas fa-paper-plane"
                                       form="replyTicketForm"/>
                @endif
            </div>
            <div>
                <a href="{{ route('user.ticket.index') }}">
                    <x-adminlte-button theme="secondary" label="Kembali" icon="fas fa-arrow-left"/>
                </a>
            </div>
        </div>
    </x-adminlte-card>
@endsection
