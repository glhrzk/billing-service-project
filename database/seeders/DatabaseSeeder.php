<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create packages
        Package::factory()->count(2)->create();

        // make roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);


        // make user for admin
        $admin = User::factory()->create([
            'name' => 'Admin Galih',
            'email' => 'admin@laravel.id'
        ]);
        // assign role to user
        $admin->assignRole('admin');

        // make user for user
        $user = User::factory()->create([
            'name' => 'User David',
            'email' => 'user@laravel.id',
            'due_date' => 5,
        ]);
        // assign role to user
        $user->assignRole('user');
    }
}
