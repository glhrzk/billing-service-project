<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'package_name_snapshot',
        'package_price_snapshot',
        'package_speed_snapshot',
        'package_description_snapshot',
        'active_discount_amount',
        'active_discount_reason',
        'active_discount_duration',
        'is_active',
    ];

    public function user()
    {
        // each user can have multiple packages
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        // each package can be used by multiple users
        return $this->belongsTo(Package::class);
    }

}
