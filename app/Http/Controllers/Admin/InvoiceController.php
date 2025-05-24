<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserBill;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $userBill = UserBill::with(['user', 'billItems'])->findOrFail($id);

        // Generate the PDF invoice using a view
        $pdf = Pdf::loadView('pdf.invoice', compact('userBill'))->setPaper('A4', 'landscape');

        // Download the PDF
        return $pdf->download("Invoice-" . str_replace(' ', '_', $userBill->user->name) . "-" . $userBill->invoice_number . ".pdf");
    }

}
