<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\UserBill;
use App\Models\UserPackage;
use Illuminate\Http\Request;

class UserBillController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $userBills = UserBill::where('user_id', $user->id)
            ->where('status', '!=', 'paid')
            ->orderBy('billing_month', 'asc')
            ->get();

        return view('user.bills.index', compact('userBills'));
    }

    public function show($id)
    {
        $user = auth()->user();

        $userBill = UserBill::with('billItems')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('user.bills.show', compact('userBill'));

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
            return redirect()->back()->with('success', 'Konfirmasi pembayaran berhasil diajukan.');
        } elseif ($originalStatus == 'rejected') {
            return redirect()->back()->with('info', 'Konfirmasi pembayaran berhasil diajukan kembali.');
        }
    }

    public function history(Request $request)
    {
        $user = auth()->user();

        $query = UserBill::where('user_id', $user->id)
            ->where('status', 'paid');

        if ($request->filled('year')) {
            $query->whereYear('billing_month', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('billing_month', $request->month);
        }

        $paidBills = $query->latest('billing_month')->get();

        $availableYears = UserBill::selectRaw('YEAR(billing_month) as year')
            ->where('user_id', $user->id)
            ->distinct()
            ->pluck('year')
            ->sortDesc();

        return view('user.bills.history', compact('paidBills', 'availableYears'));
    }


}
