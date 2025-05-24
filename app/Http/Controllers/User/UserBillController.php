<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\UserBill;
use App\Models\UserPackage;
use Illuminate\Http\Request;

class UserBillController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        // Get user bills (unpaid)
        $userBills = UserBill::where('user_id', $user->id)
            ->where('status', '!=', 'paid')
            ->orderBy('billing_month', 'asc')
            ->get();

        // Get package bills grouped by user_bill_id
        $packageBillsGroup = [];

        foreach ($userBills as $userBill) {
            $packageBillsGroup[$userBill->id] = BillItem::where('user_bill_id', $userBill->id)->get();
        }

        return view('user.bills.show', compact('user', 'userBills', 'packageBillsGroup'));

    }

    public function pay(Request $request, $billId)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer',
            'transfer_date' => 'nullable|date',
            'transfer_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userBill = UserBill::findOrFail($billId);
        $originalStatus = $userBill->status;
        $userBill->payment_method = $request->payment_method;
        $userBill->status = 'pending';

        if ($request->payment_method === 'bank_transfer') {

            if ($request->hasFile('transfer_proof')) {
                $uploadedFile = $request->file('transfer_proof');

                $filename = uniqid() . "-" . $userBill->invoice_number . '.' . $uploadedFile->getClientOriginalExtension();

                $path = $uploadedFile->storeAs('transfer_proofs', $filename, 'public');

                $userBill->transfer_proof = $path;
            }

            $userBill->transfer_date = $request->transfer_date;
        }
        $userBill->save();
        if ($originalStatus == 'unpaid') {
            return redirect()->route('user.bill.show')->with('success', 'Konfirmasi pembayaran berhasil diajukan.');
        } elseif ($originalStatus == 'rejected') {
            return redirect()->route('user.bill.show')->with('success', 'Bukti pembayaran berhasil diperbarui setelah ditolak.');
        }
    }

    public function history()
    {
        $user = auth()->user();
        $currentYear = now()->year;

        if (!request()->has('year')) {
            return redirect()->route('user.bill.history', ['year' => now()->year]);
        }


        // Ambil semua tahun tagihan
        $availableYears = UserBill::where('user_id', $user->id)
            ->where('status', 'paid')
            ->selectRaw('YEAR(billing_month) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Ambil parameter tahun, default ke tahun sekarang
        $selectedYear = request()->query('year', $currentYear);

        $query = UserBill::where('user_id', $user->id)
            ->where('status', 'paid');

        if ($selectedYear !== 'all') {
            $query->whereYear('billing_month', $selectedYear);
        }

        $userBills = $query->orderBy('billing_month', 'asc')->get();

        // Group package_bills
        $packageBillsGroup = [];
        foreach ($userBills as $userBill) {
            $packageBillsGroup[$userBill->id] = BillItem::where('user_bill_id', $userBill->id)->get();
        }

        return view('user.bills.history', compact(
            'user', 'userBills', 'packageBillsGroup', 'availableYears', 'selectedYear'
        ));
    }


}
