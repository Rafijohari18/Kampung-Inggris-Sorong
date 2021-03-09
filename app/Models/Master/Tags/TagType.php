<?php

namespace App\Models\Master\Tags;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagType extends Model
{
    use HasFactory;

    protected $table = 'tags_type';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        TagType::observe(LogObserver::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function tagable()
    {
        return $this->morphTo();
    }
}
