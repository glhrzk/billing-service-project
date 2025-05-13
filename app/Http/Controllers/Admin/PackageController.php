<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index', compact('packages'));
    }


    public function show($id)
    {
        $package = Package::findOrFail($id);

        return view('admin.packages.show', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $package = Package::findOrFail($id);

        $hasChanges = false;
        $requestData = $request->all();


        foreach ($requestData as $key => $value) {
            if ($package->$key != $value) {
                $hasChanges = true;
                break;
            }
        }

        if ($hasChanges) {
            $package->update($requestData);
            return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
        } else {
            return redirect()->route('admin.packages.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
        }
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'speed' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        Package::create($request->all());

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }
}
