<?php

namespace App\Models\Content\Post;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostProfile extends Model
{
    use HasFactory;

    protected $table = 'content_posts_profiles';
    protected $guarded = [];

    protected $casts = [
        'field' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        PostProfile::observe(LogObserver::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
