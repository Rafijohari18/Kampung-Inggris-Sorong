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

class Album extends Model
{
    use HasFactory;

    protected $table = 'gallery_albums';
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'banner' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        Album::observe(LogObserver::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function photo()
    {
        return $this->hasMany(AlbumPhoto::class, 'album_id');
    }

    public function customView()
    {
        return $this->belongsTo(TemplateView::class, 'custom_view_id');
    }

    public function photoCover($id)
    {
        $photo = AlbumPhoto::where('album_id', $id)->first();

        if (!empty($photo)) {
            $cover = Storage::url(config('custom.images.path.gallery_photo').'/'.$id.'/'.$photo->file);
        } else {
            $cover = asset(config('custom.images.file.cover_album'));
        }

        return $cover;
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(Album::class, 'id')->first()[$field][$lang];
    }

    public function routes($id)
    {
        $param = ['id' => $id];
        if (config('custom.language.multiple') == true) {
            $param = ['locale' => App::getLocale(), 'id' => $id];
        }

        return route('album.view', $param);
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
