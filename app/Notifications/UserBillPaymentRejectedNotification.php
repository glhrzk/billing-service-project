<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class UserBillPaymentRejectedNotification extends UserBillBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pembayaran Ditolak',
            'message' => 'Pembayaran untuk tagihan bulan ' .
                Carbon::parse($this->userBill->billing_month)->translatedFormat('F Y') . ' ditolak.',
            'user_bill_id' => $this->userBill->id,
            'invoice_number' => $this->userBill->invoice_number,
            'url' => route('user.bill.show', $this->userBill->id),
        ];
    }
}
