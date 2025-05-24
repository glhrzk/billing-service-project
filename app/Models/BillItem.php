<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{

    public function FinalAmount(): Attribute
    {
        return Attribute::get(
            get: fn() => ($this->billed_package_price ?? 0) - ($this->package_discount_amount ?? 0)
        );
    }

    public function userBill()
    {
        return $this->belongsTo(UserBill::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class)->withDefault(); // optional, trace asal paket
    }


}
