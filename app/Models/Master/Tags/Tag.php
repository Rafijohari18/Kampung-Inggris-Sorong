<?php

namespace App\Models\Master\Tags;

use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        Tag::observe(LogObserver::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function type()
    {
        return $this->hasMany(TagType::class, 'tag_id');
    }

    public function scopeFlags($query)
    {
        return $query->where('flags', 1);
    }

    public function scopeStandar($query)
    {
        return $query->where('standar', 1);
    }
}
