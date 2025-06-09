<?php

namespace App\Console\Commands;

use App\Models\BillItem;
use App\Models\User;
use App\Models\UserBill;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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
    protected $description = 'Generate monthly bills for all active users with their active packages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $billingDate = Carbon::createFromDate($now->year, $now->month, 1);

        $users = User::where('status', 'active')
            ->with(['userPackages'])
            ->get();

        $generatedCounter = 0;
        foreach ($users as $user) {
            $activePackages = $user->userPackages->where('is_active', 'active');

            // Lewati jika tidak ada paket aktif
            if ($activePackages->isEmpty()) {
                continue;
            }

            // Cek apakah tagihan untuk bulan ini sudah dibuat
            $alreadyExists = UserBill::where('user_id', $user->id)
                ->whereDate('billing_month', $billingDate)
                ->exists();

            if ($alreadyExists) {
                continue;
                $this->line('Tagihan untuk bulan ini sudah ada untuk user ID: ' . $user->id);
            }

            // Buat tagihan utama
            $userBill = UserBill::create([
                'user_id' => $user->id,
                'billing_month' => $billingDate->format('Y-m-d'),
                'amount' => 0, // akan dihitung setelah semua item dibuat
                'status' => 'unpaid',
                'payment_method' => 'cash', // default
                'invoice_number' => 'INV-' . $billingDate->format('Ym') . '-' . $user->id,
            ],
                $generatedCounter++
            );
            $this->line("Mencoba buat tagihan untuk user ID: {$user->id} - {$user->name}");


            $total = 0;
            $totalDiscount = 0;

            foreach ($activePackages as $userPackage) {
                $isStillInPromo = $userPackage->created_at->diffInMonths($billingDate) < ($userPackage->active_discount_duration ?? 0);
                $discountAmount = $isStillInPromo ? $userPackage->active_discount_amount : null;
                $discountReason = $isStillInPromo ? $userPackage->active_discount_reason : null;

                BillItem::create([
                    'user_bill_id' => $userBill->id,
                    'billed_package_name' => $userPackage->package_name_snapshot,
                    'billed_package_price' => $userPackage->package_price_snapshot,
                    'billed_package_speed' => $userPackage->package_speed_snapshot,
                    'billed_package_description' => $userPackage->package_description_snapshot,
                    'package_discount_amount' => $discountAmount,
                    'package_discount_reason' => $discountReason,
                ]);

                $total += $userPackage->package_price_snapshot;
                $totalDiscount += $discountAmount ?? 0;
            }

            $userBill->amount = $total - $totalDiscount;
            $userBill->save();
        }

        if ($generatedCounter < 1) {
            $this->info('Tidak ada tagihan yang dibuat. Kemungkinan semua user sudah memiliki tagihan untuk bulan ini.');
        } else {
            $this->info("Berhasil membuat {$generatedCounter} tagihan untuk bulan {$billingDate->format('F Y')}.");
        }
    }
}
