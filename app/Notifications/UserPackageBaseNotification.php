<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

abstract class UserPackageBaseNotification extends Notification
{
    protected $userBill;

    public function __construct($userBill)
    {
        $this->userBill = $userBill;
    }

    public function via($notifiable)
    {
        return ['database'];
    }
}
