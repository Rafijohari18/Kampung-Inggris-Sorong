<?php

namespace App\Models\Banner;

use App\Models\Configuration;
use App\Models\Master\Field;
use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class BannerCategory extends Model
{
    use HasFactory;

    protected $table = 'banners_categories';
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'custom_field' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        BannerCategory::observe(LogObserver::class);
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

    public function banner()
    {
        return $this->hasMany(Banner::class, 'banner_category_id');
    }

    public function bannerPublish($categoryId)
    {
        $find = BannerCategory::find($categoryId);
        $limit = Configuration::value('banner_limit');
        if (!empty($find->list_limit)) {
            $limit = $find->list_limit;
        }

        return $this->hasMany(Banner::class, 'banner_category_id')
            ->where('publish', 1)
            ->orderBy('position', 'ASC')->limit($limit);
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(BannerCategory::class, 'id')->first()[$field][$lang];
    }
}
