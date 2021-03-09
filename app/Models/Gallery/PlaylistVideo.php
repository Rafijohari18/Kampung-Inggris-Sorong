<?php

namespace App\Models\Gallery;

use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class PlaylistVideo extends Model
{
    use HasFactory;

    protected $table = 'gallery_playlists_video';
    protected $guarded = [];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        PlaylistVideo::observe(LogObserver::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class, 'playlist_id');
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(PlaylistVideo::class, 'id')->first()[$field][$lang];
    }

    public function videoType($item)
    {
        if (!empty($item->file) && empty($item->youtube_id)) {

            if (!empty($item->thumbnail)) {
                $bg = Storage::url(config('custom.images.path.gallery_video').$item->playlist_id.'/thumbnail/'.$item->thumbnail);
            } else {
                $bg = asset(config('custom.images.file.cover_playlist'));
            }

            $type = [
                'background' => $bg,
                'video' => Storage::url(config('custom.images.path.gallery_video').$item->playlist_id.'/'.$item->file),
                'name' => 'VIDEO'
            ];

        } elseif (empty($item->file) && !empty($item->youtube_id)) {

            $type = [
                'background' => 'https://i.ytimg.com/vi/'.$item->youtube_id.'/mqdefault.jpg',
                'video' => 'https://www.youtube.com/embed/'.$item->youtube_id.'?rel=0;showinfo=0',
                'name' => 'YOUTUBE VIDEO'
            ];
        }

        return $type;
    }
}
