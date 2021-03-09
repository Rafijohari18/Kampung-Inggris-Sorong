<?php

namespace App\Models\Master;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        Field::observe(LogObserver::class);
    }

    public function fieldable()
    {
        return $this->morphTo();
    }
}
