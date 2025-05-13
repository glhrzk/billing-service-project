<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        // Fetch users from the database
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->get();
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        // Fetch user by ID
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'due_date' => 'required|numeric',
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        // Fetch user by ID
        $user = User::findOrFail($id);


        // check any changes or not
        $hasChanges = false;
        $requestData = $request->only([
            'name', 'email', 'phone', 'address', 'due_date', 'status'
        ]);

        if ($request->filled('password')) {
            $requestData['password'] = $request->password;
        }

        foreach ($requestData as $key => $value) {
            if ($user->$key != $value) {
                $hasChanges = true;
                break;
            }
        }

        if ($hasChanges) {
            // Update user data
            $user->update($requestData);
            return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diubah');
        } else {
            return redirect()->back()->with('info', 'Tidak ada perubahan data');
        }

    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'due_date' => 'nullable|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create($request->all())->assignRole('user');


        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }
}
