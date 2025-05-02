<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    public function userPackages()
    {
        // each package has many user packages
        return $this->hasMany(UserPackage::class);
    }

}
