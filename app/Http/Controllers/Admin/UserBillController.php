<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageBill;
use App\Models\UserBill;
use Illuminate\Http\Request;

class UserBillController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->get('year');
        $selectedMonth = $request->get('month');
        $searchUser = $request->get('user');

        $query = UserBill::with(['user', 'packageBills']);

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
        $userBill = UserBill::with(['user', 'packageBills'])->findOrFail($id);
//        dd($userBill);
        return view('admin.bills.show', compact('userBill'));
    }


}
