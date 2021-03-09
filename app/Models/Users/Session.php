<?php

namespace App\Models\Users;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';
    protected $guarded = [];

    protected $casts = [
        'last_activity' => 'datetime'
    ];

    public static function boot()
    {
        parent::boot();

        Session::observe(LogObserver::class);
    }
}
