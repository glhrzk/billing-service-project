<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'speed', 'description', 'price', 'status'];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_packages');
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }


}
