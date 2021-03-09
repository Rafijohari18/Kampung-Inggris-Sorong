<?php

namespace App\Models\Catalog;

use App\Models\Configuration;
use App\Models\Master\Field;
use App\Models\Master\Tags\TagType;
use App\Models\Master\TemplateView;
use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CatalogProduct extends Model
{
    use HasFactory;

    protected $table = 'catalogue_products';
    protected $guarded = [];

    protected $casts = [
        'title' => 'array',
        'intro' => 'array',
        'content' => 'array',
        'cover' => 'array',
        'banner' => 'array',
        'meta_data' => 'array',
        'custom_field' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        CatalogProduct::observe(LogObserver::class);
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

    public function images()
    {
        return $this->hasMany(CatalogProductImage::class, 'catalog_product_id');
    }

    public function tags()
    {
        return $this->morphMany(TagType::class, 'tagable');
    }

    public function category()
    {
        return $this->belongsTo(CatalogCategory::class, 'catalog_category_id');
    }

    public function customView()
    {
        return $this->belongsTo(TemplateView::class, 'custom_view_id');
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(CatalogProduct::class, 'id')->first()[$field][$lang];
    }

    public function routes($id, $slug)
    {
        $param = ['id' => $id, 'slug' => $slug];
        if (config('custom.language.multiple') == true) {
            $param = ['locale' => App::getLocale(), 'id' => $id, 'slug' => $slug];
        }

        return route('catalog.product.view', $param);
    }

    public function scopePublish($query)
    {
        return $query->where('publish', 1);
    }

    public function scopePublic($query)
    {
        return $query->where('public', 1);
    }

    public function scopeSelection($query)
    {
        return $query->where('selection', 1);
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
