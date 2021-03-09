<?php

namespace App\Models\Inquiry;

use App\Models\Configuration;
use App\Models\Master\Field;
use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiries';
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'body' => 'array',
        'after_body' => 'array',
        'banner' => 'array',
        'custom_field' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        Inquiry::observe(LogObserver::class);
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

    public function message()
    {
        return $this->hasMany(InquiryMessage::class, 'inquiry_id');
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(Inquiry::class, 'id')->first()[$field][$lang];
    }

    public function routes($slug)
    {
        $param = ['slug' => $slug];
        if (config('custom.language.multiple') == true) {
            $param = ['locale' => App::getLocale(), 'slug' => $slug];
        }

        return route('inquiry.view', $param);
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
