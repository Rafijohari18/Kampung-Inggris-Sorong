<?php

namespace App\Models\Banner;

use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $guarded = [];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'custom_field' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        Banner::observe(LogObserver::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function category()
    {
        return $this->belongsTo(BannerCategory::class, 'banner_category_id');
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(Banner::class, 'id')->first()[$field][$lang];
    }

    public function scopeVideo($query)
    {
        return $query->where('is_video', 1);
    }

    public function scopePublish($query)
    {
        return $query->where('publish', 1);
    }

    public function bannerType($item)
    {
        if ($item->is_video == 0 && !empty($item->file) && empty($item->youtube_id)) {

            $type = [
                'background' => Storage::url(config('custom.images.path.banner').$item->banner_category_id.'/'.$item->file),
                'name' => 'IMAGE'
            ];

        } elseif ($item->is_video == 1 && !empty($item->file) && empty($item->youtube_id)) {

            $type = [
                'background' => asset(config('custom.images.file.cover_playlist')),
                'video' => Storage::url(config('custom.images.path.banner').$item->banner_category_id.'/'.$item->file),
                'name' => 'VIDEO'
            ];

        } elseif ($item->is_video == 1 && empty($item->file) && !empty($item->youtube_id)) {

            $type = [
                'background' => 'https://i.ytimg.com/vi/'.$item->youtube_id.'/mqdefault.jpg',
                'video' => 'https://www.youtube.com/embed/'.$item->youtube_id.'?rel=0;showinfo=0',
                'name' => 'YOUTUBE VIDEO'
            ];
        }

        return $type;
    }
}
