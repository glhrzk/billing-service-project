<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class TicketResolvedNotification extends TicketBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Ticket ditutup',
            'message' => 'Ticket #' . $this->ticket->id . ' telah ditutup pada ' .
                Carbon::now()->translatedFormat('l, d F Y H:i'),
            'ticket_id' => $this->ticket->id,
            'url' => route('user.ticket.reply.show', $this->ticket->id),
        ];
    }
}
