<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Package;
use App\Models\UserPackage;
use App\Models\UserBill;
use App\Models\BillItem;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        // Admin user
        $admin = User::factory()->create([
            'name' => 'Admin Galih',
            'email' => 'admin@laravel.id',
        ]);
        $admin->assignRole('admin');

        // Regular user
        $user = User::factory()->create([
            'name' => 'User David',
            'email' => 'user@laravel.id',
            'due_date' => 5,
        ]);
        $user->assignRole('user');

        // Paket-paket
        $packages = Package::factory()->count(3)->sequence(
            ['name' => 'Mini', 'price' => 100000, 'speed' => '10Mbps'],
            ['name' => 'Super', 'price' => 150000, 'speed' => '20Mbps'],
            ['name' => 'Mega', 'price' => 250000, 'speed' => '50Mbps'],
        )->create();

        $mini = $packages->firstWhere('name', 'Mini');
        $super = $packages->firstWhere('name', 'Super');

        // UserPackage aktif
        $miniUserPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $mini->id,
            'package_name_snapshot' => $mini->name,
            'package_price_snapshot' => $mini->price,
            'package_speed_snapshot' => $mini->speed,
            'package_description_snapshot' => $mini->description,
            'active_discount_amount' => 15000,
            'active_discount_reason' => 'Promo Kemerdekaan',
            'active_discount_duration' => 2,
            'is_active' => 'active',
        ]);

        $superUserPackage = UserPackage::create([
            'user_id' => $user->id,
            'package_id' => $super->id,
            'package_name_snapshot' => $super->name,
            'package_price_snapshot' => $super->price,
            'package_speed_snapshot' => $super->speed,
            'package_description_snapshot' => $super->description,
            'is_active' => 'inactive',
        ]);

        // Billing per bulan
        $billingMonths = [
            '2023-10-01',
            '2023-11-01',
        ];

        Ticket::create([
            'user_id' => $user->id,
            'subject' => 'Test Ticket',
            'description' => 'This is a test ticket.',
            'status' => 'open',
        ]);

        foreach ($billingMonths as $billingMonth) {
            $billingDate = Carbon::parse($billingMonth);

            $userBill = UserBill::create([
                'user_id' => $user->id,
                'billing_month' => $billingMonth,
                'amount' => 0,
                'discount_amount' => null,
                'discount_reason' => null,
                'status' => 'unpaid',
                'payment_method' => 'cash',
                'invoice_number' => 'INV-' . $billingDate->format('Ym') . '-' . $user->id,
            ]);

            $activePackages = [];
            if ($billingMonth === '2023-10-01') {
                $activePackages = [$miniUserPackage, $superUserPackage];
            } elseif ($billingMonth === '2023-11-01') {
                $activePackages = [$miniUserPackage];
            }

            foreach ($activePackages as $userPackage) {
                $isStillInPromo = $userPackage->created_at->diffInMonths($billingDate) < ($userPackage->active_discount_duration ?? 0);
                $discount = $isStillInPromo ? $userPackage->active_discount_amount : null;

                BillItem::create([
                    'user_bill_id' => $userBill->id,
                    'package_id' => $userPackage->package_id,
                    'billed_package_name' => $userPackage->package_name_snapshot,
                    'billed_package_price' => $userPackage->package_price_snapshot,
                    'billed_package_speed' => $userPackage->package_speed_snapshot,
                    'billed_package_description' => $userPackage->package_description_snapshot,
                    'package_discount_amount' => $discount,
                    'package_discount_reason' => $userPackage->active_discount_reason,
                ]);
            }
            $userBill->amount = collect($userBill->billItems)->sum('billed_package_price');
            $userBill->update();
        }
    }
}
