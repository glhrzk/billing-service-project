<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class TicketRepliedByAdminNotification extends TicketBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Tiket Dibalas',
            'message' => 'Tiket ' . $this->ticket->subject . ' telah dibalas oleh Admin pada ' .
                Carbon::now()->translatedFormat('l, d F Y H:i'),
            'ticket_id' => $this->ticket->id,
            'url' => route('user.ticket.reply.show', $this->ticket->id),
        ];
    }
}
