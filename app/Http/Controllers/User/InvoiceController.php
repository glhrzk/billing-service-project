<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\UserBill;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceController extends Controller
{
    public function download($billId)
    {
        $user = auth()->user();
        $userBill = UserBill::findOrFail($billId);

        $pdf = Pdf::loadView('pdf.invoice', compact('userBill'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download($user->name . '-invoice-' . $userBill->invoice_number . '.pdf');
    }
}
