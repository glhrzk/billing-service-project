<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'status',
    ];

    public function user()
    {
        // each ticket is created by a user
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        // each ticket can have multiple replies
        return $this->hasMany(TicketReply::class);
    }

}
