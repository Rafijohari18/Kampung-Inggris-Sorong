<?php

namespace App\Models\Catalog;

use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CatalogProductImage extends Model
{
    use HasFactory;

    protected $table = 'catalogue_products_images';
    protected $guarded = [];

    protected $casts = [
        'meta_file' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        CatalogProductImage::observe(LogObserver::class);
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function product()
    {
        return $this->belongsTo(CatalogProduct::class, 'catalog_product_id');
    }

    public function fileType($item)
    {
        if ($item->is_video == 0) {

            $type = [
                'background' => Storage::url(config('custom.images.path.catalog_product').$item->catalog_product_id.'/'.$item->file),
                'name' => 'IMAGE'
            ];

        } elseif ($item->is_video == 1) {

            if (!empty($item->thumbnail)) {
                $bg = Storage::url(config('custom.images.path.catalog_product').$item->catalog_product_id.'/thumbnail/'.$item->thumbnail);;
            } else {
                $bg = asset(config('custom.images.file.cover_playlist'));
            }

            $type = [
                'background' => $bg,
                'video' => Storage::url(config('custom.images.path.banner').$item->banner_category_id.'/'.$item->file),
                'name' => 'VIDEO'
            ];

        }

        return $type;
    }
}
