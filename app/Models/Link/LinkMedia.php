<?php

namespace App\Models\Link;

use App\Models\Configuration;
use App\Models\Master\Field;
use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class LinkMedia extends Model
{
    use HasFactory;

    protected $table = 'links_media';
    protected $guarded = [];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'cover' => 'array',
        'banner' => 'array',
        'custom_field' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        LinkMedia::observe(LogObserver::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function field()
    {
        return $this->morphMany(Field::class, 'fieldable');
    }

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id');
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(LinkMedia::class, 'id')->first()[$field][$lang];
    }

    public function coverSrc($item)
    {
        if (!empty($item->cover['file_path'])) {
            $cover = Storage::url(config('custom.images.path.filemanager').$item->cover['file_path']);
        } else {
            $cover = asset(config('custom.images.file.cover'));
        }

        return $cover;
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
