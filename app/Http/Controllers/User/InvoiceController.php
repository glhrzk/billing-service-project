<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\UserBill;
use Barryvdh\DomPDF\Facade\Pdf;


class InvoiceController extends Controller
{
    public function show($billId)
    {
        $user = auth()->user();
        $userBill = UserBill::findOrFail($billId);
        $packageBillsGroup = BillItem::where('user_bill_id', $billId)->get();

        return view('user.invoices.show', compact('user', 'userBill', 'packageBillsGroup'));
    }

    public function download($billId)
    {
        $user = auth()->user();
        $userBill = UserBill::findOrFail($billId);
        $packageBillsGroup = BillItem::where('user_bill_id', $billId)->get();

        $pdf = Pdf::loadView('user.invoices.download', compact('user', 'userBill', 'packageBillsGroup'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download($user->name . '-invoice-' . $userBill->invoice_number . '.pdf');
    }
}
