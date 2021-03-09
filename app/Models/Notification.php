<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $guarded = [];

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'user_from');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'user_to');
    }
}
