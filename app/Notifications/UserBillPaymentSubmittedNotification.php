<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class UserBillPaymentSubmittedNotification extends UserBillBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pembayaran Diproses',
            'message' => 'Pembayaran untuk tagihan bulan ' .
                Carbon::parse($this->userBill->billing_month)->translatedFormat('F Y') . ' sedang diproses.',
            'user_bill_id' => $this->userBill->id,
            'invoice_number' => $this->userBill->invoice_number,
            'url' => route('admin.bill.show', $this->userBill->id),
        ];
    }
}
