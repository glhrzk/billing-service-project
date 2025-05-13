<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class UserBillPaymentCreatedNotification extends UserBillBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Tagihan Dibuat',
            'message' => 'Tagihan baru ' .
                Carbon::parse($this->userBill->billing_month)->translatedFormat('F Y') . ' sudah tersedia.',
            'user_bill_id' => $this->userBill->id,
            'invoice_number' => $this->userBill->invoice_number,
            'url' => route('user.bill.show', $this->userBill->id),
        ];
    }
}
