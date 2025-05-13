<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBill extends Model
{

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function userPackage()
    {
        // each package bill belongs to a user package
        return $this->belongsTo(UserPackage::class);
    }

    public function packageBills()
    {
        return $this->hasMany(PackageBill::class);
    }

}
