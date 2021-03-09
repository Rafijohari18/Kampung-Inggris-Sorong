<?php

namespace App\Models\Content\Post;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostFile extends Model
{
    use HasFactory;

    protected $table = 'content_posts_files';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        PostFile::observe(LogObserver::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function filePath($item)
    {
        return Storage::url(config('custom.images.path.extra_file').$item->post_id.'/'.$item->file);
    }
}
