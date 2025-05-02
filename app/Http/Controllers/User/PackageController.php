<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function show()
    {
        // Show the user's package active details
        $user = auth()->user()->userPackages()->where('is_active', 'active')->get();
        return view('user.packages.show', compact('user'));
    }

    public function history()
    {
        $user = auth()->user()->userPackages;
        return view('user.packages.history', compact('user'));
    }
}
