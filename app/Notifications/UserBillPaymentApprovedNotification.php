<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;

class UserBillPaymentApprovedNotification extends UserBillBaseNotification
{
    use Queueable;

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Pembayaran Diterima',
            'message' => 'Pembayaran untuk tagihan bulan ' .
                Carbon::parse($this->userBill->billing_month)->translatedFormat('F Y') . ' diterima.',
            'user_bill_id' => $this->userBill->id,
            'invoice_number' => $this->userBill->invoice_number,
            'url' => route('user.bill.show', $this->userBill->id),
        ];
    }
}
