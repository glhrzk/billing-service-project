<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBill extends Model
{
    /** @use HasFactory<\Database\Factories\UserBillFactory> */
    use HasFactory;

    protected $fillable = [
        'discount_amount',
        'discount_reason',
        'status',
        'payment_status',
        'tranfer_date',
        'transfer_proof',
        'paid_at'
    ];

    public function finalAmount(): Attribute
    {
        return Attribute::get(
            fn() => ($this->amount ?? 0) - ($this->discount_amount ?? 0)
        );
    }


    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function income()
    {
        return $this->hasOne(Income::class, 'bill_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
