<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBill extends Model
{
    //
    public function userPackage()
    {
        // each package bill belongs to a user package
        return $this->belongsTo(UserPackage::class);
    }
}
