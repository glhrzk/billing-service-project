<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillItem;
use App\Models\Income;
use App\Models\UserBill;
use Illuminate\Http\Request;

class UserBillController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('year');
        $selectedMonth = $request->get('month');
        $searchUser = $request->get('user');
        $selectedStatus = $request->get('status');

        $query = UserBill::with(['user', 'billItems']);

        if ($selectedYear) {
            $query->whereYear('billing_month', $selectedYear);
        }

        if ($selectedMonth) {
            $query->whereMonth('billing_month', $selectedMonth);
        }

        if ($searchUser) {
            $query->whereHas('user', function ($q) use ($searchUser) {
                $q->where('name', 'like', '%' . $searchUser . '%');
            });
        }

        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        } else {
            $query->whereIn('status', ['unpaid', 'paid', 'rejected']);
        }

        $userBills = $query->orderBy('billing_month', 'desc')->get();

        $availableYears = UserBill::selectRaw('YEAR(billing_month) as year')
            ->whereNotNull('billing_month')
            ->distinct()
            ->pluck('year')
            ->toArray();

        return view('admin.bills.index', compact('userBills', 'availableYears', 'selectedYear', 'selectedMonth', 'searchUser'));
    }

    public function show($id)
    {
        $userBill = UserBill::with(['user', 'billItems'])->findOrFail($id);
        return view('admin.bills.show', compact('userBill'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'discount_amount' => 'nullable|numeric|min:0|required_with:discount_reason',
            'discount_reason' => 'nullable|string|max:255|required_with:discount_amount',
        ]);

        $userBill = UserBill::findOrFail($id);

        if ($userBill->status !== 'unpaid') {
            return redirect()->back()->with('error', 'Tagihan tidak dapat diperbarui karena ' . payment_status_label($userBill->status) . '.');
        } else {
            $userBill->update([
                'discount_amount' => $request->input('discount_amount'),
                'discount_reason' => $request->input('discount_reason'),
            ]);
        }
        return redirect()->back()->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function verification()
    {
        $userBills = UserBill::where('status', 'pending')->with(['user'])->orderBy('billing_month', 'desc')->get();
        return view('admin.bills.verification', compact('userBills'));
    }

    public function verify($id)
    {
        $userBill = UserBill::findOrFail($id);
        return view('admin.bills.verify', compact('userBill'));
    }

    public function verifyAction(Request $request, $id)
    {
        $userBill = UserBill::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->input('action') === 'approve') {
            $userBill->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            Income::create([
                "user_bill_id" => $userBill->id,
                "date" => now(),
                "amount" => $userBill->final_amount,
                "description" => "Tagihan {$userBill->user->name} untuk bulan {$userBill->billing_month}",
            ]);

            return redirect()->route('admin.bills.index')->with('success', 'Tagihan berhasil disetujui.');
        } elseif ($request->input('action') === 'reject') {
            $userBill->update(['status' => 'rejected']);
            return redirect()->route('admin.bills.index')->with('info', 'Tagihan berhasil ditolak.');
        }

        return redirect()->back()->with('error', 'Aksi tidak valid.');
    }
}
