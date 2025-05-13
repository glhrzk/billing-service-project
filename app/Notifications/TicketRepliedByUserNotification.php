<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class TicketRepliedByUserNotification extends TicketBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Tiket Dibalas',
            'message' => 'Tiket ' . $this->ticket->subject . ' telah dibalas oleh User' .
                Carbon::now()->translatedFormat('l, d F Y H:i'),
            'ticket_id' => $this->ticket->id,
            'url' => route('admin.ticket.reply.show', $this->ticket->id),
        ];
    }
}
