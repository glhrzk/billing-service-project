<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        // make roles
//        Role::create(['name' => 'admin']);
//        Role::create(['name' => 'user']);
//
//
//        // make user for admin
//        $admin = User::factory()->create([
//            'name' => 'Admin Galih',
//            'email' => 'admin@laravel.id'
//        ]);
//        // assign role to user
//        $admin->assignRole('admin');
//
//        // make user for user
//        $user = User::factory()->create([
//            'name' => 'User David',
//            'email' => 'user@laravel.id',
//            'due_date' => 5,
//        ]);
//        // assign role to user
//        $user->assignRole('user');
    }
}
