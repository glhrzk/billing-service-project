<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\UserBill;
use App\Models\PackageBill;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin Galih',
            'email' => 'admin@laravel.id',
        ]);
        $admin->assignRole('admin');

        // Create regular user
        $user = User::factory()->create([
            'name' => 'User David',
            'email' => 'user@laravel.id',
            'due_date' => 5,
        ]);
        $user->assignRole('user');

        // Create packages
        $packages = Package::factory()->count(3)->sequence(
            ['name' => 'Mini', 'price' => 100000, 'speed' => '10Mbps'],
            ['name' => 'Super', 'price' => 150000, 'speed' => '20Mbps'],
            ['name' => 'Mega', 'price' => 250000, 'speed' => '50Mbps'],
        )->create();

        // Create user_packages
        $miniPackage = $packages->firstWhere('name', 'Mini');
        $superPackage = $packages->firstWhere('name', 'Super');

        $miniUserPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $miniPackage->id,
            'locked_name' => $miniPackage->name,
            'locked_price' => $miniPackage->price,
            'locked_speed' => $miniPackage->speed,
            'locked_description' => $miniPackage->description,
            'is_active' => 'active',
        ]);

        $superUserPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $superPackage->id,
            'locked_name' => $superPackage->name,
            'locked_price' => $superPackage->price,
            'locked_speed' => $superPackage->speed,
            'locked_description' => $superPackage->description,
            'is_active' => 'inactive', // Di bulan kedua, dianggap sudah tidak aktif
        ]);

        // Billing months
        $billingMonths = [
            '2023-10-01', // Bulan 1
            '2023-11-01', // Bulan 2
        ];

        foreach ($billingMonths as $billingMonth) {
            $billingDate = Carbon::parse($billingMonth);

            // Create UserBill
            $userBill = UserBill::create([
                'user_id' => $user->id,
                'billing_month' => $billingMonth,
                'amount' => 0, // akan dihitung setelah semua package_bills dibuat
                'status' => 'unpaid',
                'payment_method' => 'cash',
                'invoice_number' => 'INV-' . $billingDate->format('Ym') . '-' . $user->id,
            ]);

            // Logic paket yang aktif berdasarkan bulan
            $activePackages = [];
            if ($billingMonth == '2023-10-01') {
                $activePackages = [$miniUserPackage, $superUserPackage];
            } elseif ($billingMonth == '2023-11-01') {
                $activePackages = [$miniUserPackage]; // hanya Mini saja aktif
            }

            $totalFinalAmount = 0;

            foreach ($activePackages as $userPackage) {
                $lockedPrice = $userPackage->locked_price;
                $discountAmount = 0; // tidak ada diskon
                $finalAmount = $lockedPrice - $discountAmount;

                PackageBill::create([
                    'user_bill_id' => $userBill->id,
                    'locked_name' => $userPackage->locked_name,
                    'locked_price' => $lockedPrice,
                    'locked_speed' => $userPackage->locked_speed,
                    'locked_description' => $userPackage->locked_description,
                    'discount_amount' => $discountAmount,
                    'final_amount' => $finalAmount,
                ]);

                $totalFinalAmount += $finalAmount;
            }

            // Update total amount di user_bill
            $userBill->update([
                'amount' => $totalFinalAmount,
            ]);
        }
    }
}
