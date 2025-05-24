<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_bill_id',
        'date',
        'amount',
        'description',
    ];

    public function userBill()
    {
        // each income can be associated with a user bill
        return $this->belongsTo(UserBill::class);
    }

}
