<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $userPackages = $user->userPackages()
            ->where('is_active', 'active')
            ->latest()
            ->get();

        return view('user.packages.index', compact('userPackages'));
    }

    public function history()
    {
        $user = auth()->user();
        $userPackages = $user->userPackages()
            ->latest()
            ->get();
        return view('user.packages.history', compact('userPackages'));
    }
}
