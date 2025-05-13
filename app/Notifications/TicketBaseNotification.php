<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

abstract class TicketBaseNotification extends Notification
{
    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database'];
    }
}
