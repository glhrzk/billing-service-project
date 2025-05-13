<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBill extends Model
{
    /** @use HasFactory<\Database\Factories\UserBillFactory> */
    use HasFactory;

    public function user()
    {
        // each user can have multiple bills
        return $this->belongsTo(User::class);
    }

    public function invoices()
    {
        // each bill can have multiple invoices
        return $this->hasOne(Invoice::class);
    }

    public function incomes()
    {
        // each bill can have multiple incomes
        return $this->hasOne(Income::class);
    }

    public function packageBills()
    {
        return $this->hasMany(PackageBill::class);
    }


}
