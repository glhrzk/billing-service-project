<?php

namespace App\Console\Commands;

use App\Models\BillItem;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateBillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-bills-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $year = $now->year;
        $month = $now->month;

        $users = User::where('status', 'active')
            ->with(['userPackages.package'])
            ->get();

        foreach ($users as $user) {
            foreach ($user->userPackages as $userPackage) {

                // Cek apakah tagihan untuk bulan ini sudah dibuat
                $alreadyExists = UserBill::where('user_id', $user->id)
                    ->where('user_package_id', $userPackage->id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                // Buat user_bill
                $userBill = UserBill::create([
                    'user_id' => $user->id,
                    'user_package_id' => $userPackage->id,
                    'year' => $year,
                    'month' => $month,
                    'status' => 'unpaid',
                    'discount' => $userPackage->discount, // diskon dari paket user (nullable)
                ]);

                // Buat bill item
                BillItem::create([
                    'user_bill_id' => $userBill->id,
                    'package_id' => $userPackage->package_id,
                    'price' => $userPackage->package->price,
                    'discount' => 0, // jika ada diskon insidental, bisa disesuaikan nanti
                ]);

                // Buat invoice
                Invoice::create([
                    'user_bill_id' => $userBill->id,
                    'invoice_number' => 'INV-' . strtoupper(uniqid($user->id . '-')),
                    'issued_date' => $now,
                    'due_date' => $now->copy()->addDays(7),
                ]);
            }
        }

        $this->info("Tagihan berhasil digenerate untuk bulan {$month}/{$year}");
    }
}
