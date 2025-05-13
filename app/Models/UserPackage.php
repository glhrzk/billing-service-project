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
        'locked_name',
        'locked_price',
        'locked_speed',
        'locked_description',
        'initial_discount_amount',
        'initial_discount_reason',
        'initial_discount_duration',
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
