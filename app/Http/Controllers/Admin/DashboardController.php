<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserBill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $totalUsers = User::role('user')->count();
        $totalPackages = Package::count();

        $billingThisMonth = UserBill::whereMonth('billing_month', $currentMonth)
            ->whereYear('billing_month', $currentYear)
            ->get()
            ->sum(fn($bill) => ($bill->amount ?? 0) - ($bill->discount_amount ?? 0));

        $paymentsThisMonth = UserBill::where('status', 'paid')
            ->whereMonth('paid_at', $currentMonth)
            ->whereYear('paid_at', $currentYear)
            ->get()
            ->sum(fn($bill) => ($bill->amount ?? 0) - ($bill->discount_amount ?? 0));

        $latestTickets = Ticket::with('user')->latest()->take(5)->get();

        $pendingBills = UserBill::with('user')
            ->where('status', 'pending')
            ->orderByDesc('transfer_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPackages',
            'billingThisMonth',
            'paymentsThisMonth',
            'latestTickets',
            'pendingBills'
        ));
    }

    private function getMonthLabels()
    {
        $labels = [];
        for ($i = 0; $i < 12; $i++) {
            $labels[] = now()->subMonths(11 - $i)->format('M Y');
        }
        return $labels;
    }

    private function getMonthlyRevenue()
    {
        $revenues = [];
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths(11 - $i);
            $total = \App\Models\UserBill::where('status', 'paid')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->get()
                ->sum(function ($bill) {
                    return ($bill->amount ?? 0) - ($bill->discount_amount ?? 0);
                });

            $revenues[] = $total;
        }
        return $revenues;
    }


}
