<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\UserBill;
use App\Models\UserPackage;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Semua tagihan aktif (unpaid & pending)
        $activeBill = UserBill::where('user_id', $userId)
            ->whereIn('status', ['unpaid', 'pending', 'rejected'])
            ->orderBy('billing_month', 'asc')
            ->get();

        // Paket aktif
        $activePackage = UserPackage::where('user_id', $userId)
            ->where('is_active', 'active')
            ->first();

        // Riwayat pembayaran terakhir (maks 3)
        $recentPayments = UserBill::where('user_id', $userId)
            ->where('status', 'paid')
            ->latest('updated_at')
            ->take(3)
            ->get();

        // Tiket bantuan terakhir (maks 3)
        $recentTickets = Ticket::where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        return view('user.dashboard', compact(
            'activeBill',
            'activePackage',
            'recentPayments',
            'recentTickets'
        ));
    }

}
