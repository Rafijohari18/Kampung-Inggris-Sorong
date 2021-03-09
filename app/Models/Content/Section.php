<?php

namespace App\Models\Content;

use App\Models\Configuration;
use App\Models\Content\Post\Post;
use App\Models\Master\Field;
use App\Models\Master\TemplateView;
use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Section extends Model
{
    use HasFactory;

    protected $table = 'content_sections';
    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'banner' => 'array',
        'custom_field' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        Section::observe(LogObserver::class);
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

    public function category()
    {
        return $this->hasMany(Category::class, 'section_id');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'section_id');
    }

    public function listView()
    {
        return $this->belongsTo(TemplateView::class, 'list_view_id');
    }

    public function detailView()
    {
        return $this->belongsTo(TemplateView::class, 'detail_view_id');
    }

    public function fieldLang($field, $lang = null)
    {
        if ($lang == null) {
            $lang = App::getLocale();
        }

        return $this->hasMany(Section::class, 'id')->first()[$field][$lang];
    }

    public function routes($id, $slug)
    {
        $param = ['id' => $id, 'slug' => $slug];
        if (config('custom.language.multiple') == true) {
            $param = ['locale' => App::getLocale(), 'id' => $id, 'slug' => $slug];
        }

        return route('section.view', $param);
    }

    public function scopePublic($query)
    {
        return $query->where('public', 1);
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
