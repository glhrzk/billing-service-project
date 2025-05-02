<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function userBill()
    {
        // each invoice belongs to a user bill
        return $this->belongsTo(UserBill::class);
    }

}
