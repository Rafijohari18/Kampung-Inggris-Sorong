<?php

namespace App\Models\Gallery;

use App\Models\Configuration;
use App\Models\Master\TemplateView;
use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Playlist extends Model
{
    use HasFactory;

    protected $table = 'gallery_playlists';
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'banner' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        Playlist::observe(LogObserver::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function video()
    {
        return $this->hasMany(PlaylistVideo::class, 'playlist_id');
    }

    public function customView()
    {
        return $this->belongsTo(TemplateView::class, 'custom_view_id');
    }

    public function videoCover($id)
    {
        $video = PlaylistVideo::where('playlist_id', $id)->first();

        if (!empty($video)) {
            if (!empty($video->file)) {
                $cover = Storage::url(config('custom.images.path.gallery_video').'/'.$id.'/thumbnail/'.$video->file);
            } elseif (!empty($video->youtube_id)) {
                $cover = 'https://i.ytimg.com/vi/'.$video->youtube_id.'/mqdefault.jpg';
            } else {
                $cover = asset(config('custom.images.file.cover_playlist'));
            }
        } else {
            $cover = asset(config('custom.images.file.cover_playlist'));
        }

        return $cover;
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(Playlist::class, 'id')->first()[$field][$lang];
    }

    public function routes($id)
    {
        $param = ['id' => $id];
        if (config('custom.language.multiple') == true) {
            $param = ['locale' => App::getLocale(), 'id' => $id];
        }

        return route('playlist.view', $param);
    }

    public function scopePublish($query)
    {
        return $query->where('publish', 1);
    }

    public function bannerSrc($item)
    {
        if (!empty($item->banner['file_path'])) {
            $banner = Storage::url(config('custom.images.path.filemanager').$item->banner['file_path']);
        } else {
            if (!empty(Configuration::value('banner_default'))) {
                $banner = Storage::url(config('custom.images.path.banner').Configuration::value('banner_default'));
            } else {
                $banner = asset(config('custom.images.file.banner_default'));
            }
        }

        return $banner;
    }
}
