<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class TicketCreatedNotification extends TicketBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Tiket Baru Dibuat',
            'message' => 'Tiket baru dengan ID ' . $this->ticket->id . ' telah dibuat oleh ' .
                $this->ticket->user->name . ' pada ' .
                Carbon::parse($this->ticket->created_at)->translatedFormat('l, d F Y H:i'),
            'ticket_id' => $this->ticket->id,
            'url' => route('admin.ticket.reply.show', $this->ticket->id),
        ];
    }
}
