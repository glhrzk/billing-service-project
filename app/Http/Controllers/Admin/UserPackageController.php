<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\Request;


class UserPackageController extends Controller
{
    public function index()
    {
        $userPackages = UserPackage::with('user')->where('is_active', '=', 'active')->latest()->get();
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->get();

        $packages = Package::where('status', 'active')->get();

        return view('admin.user-packages.index', compact('userPackages', 'users', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'active_discount_amount' => 'nullable|numeric',
            'active_discount_reason' => 'nullable|string|max:255',
            'active_discount_duration' => 'nullable|numeric|min:1|max:12',
        ]);

        // Check if the user already has an active package
        $existingPackage = UserPackage::where('package_id', $request->package_id)->first();

        // find the package
        $package = Package::findOrFail($request->package_id);

        // filled the request with package data
        $request->merge([
            'package_name_snapshot' => $package->name,
            'package_price_snapshot' => $package->price,
            'package_speed_snapshot' => $package->speed,
            'package_description_snapshot' => $package->description,
            'is_active' => 'active',
        ]);


        if ($existingPackage) {
            if ($existingPackage->is_active == 'active') {
                return redirect()->back()->with('error', 'Pengguna sudah memiliki paket tersebut.');
            } else {
                // Update the existing package to inactive
                $existingPackage->update([
                    'package_name_snapshot' => $package->name,
                    'package_price_snapshot' => $package->price,
                    'package_speed_snapshot' => $package->speed,
                    'package_description_snapshot' => $package->description,
                    'is_active' => 'active',
                ]);
                return redirect()->route('admin.user-packages.index')->with('success', 'Paket berhasil diperbarui.');
            }
        }

        if ($request->active_discount_amount == null) {
            $request->merge([
                'active_discount_amount' => null,
                'active_discount_reason' => null,
                'active_discount_duration' => null,
            ]);
        }
        // Create a new user package
        UserPackage::create($request->all());

        return redirect()->route('admin.user-packages.index')->with('success', 'Paket berhasil ditambahkan.');

    }

    public function update(Request $request, $id)
    {
        UserPackage::findOrFail($id)->update([
            'is_active' => 'inactive',
        ]);

        return redirect()->route('admin.user-packages.index')->with('success', 'Paket user berhasil di nonaktifkan.');
    }

    function show($id)
    {
        // Fetch user package by ID
        $userPackage = UserPackage::with('user', 'package')->findOrFail($id);
        return view('admin.user-packages.show', compact('userPackage'));
    }
}
