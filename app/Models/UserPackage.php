<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

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

    public function packageBills()
    {
        // each package can have multiple bills
        return $this->hasMany(PackageBill::class);
    }

}
