<?php

namespace App\Models;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configurations';
    protected $primaryKey = 'name';
    protected $guarded = [];

    public $incrementing = false;
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        Configuration::observe(LogObserver::class);
    }

    public function scopeUpload($query)
    {
        return $query->where('is_upload', 1);
    }

    static function value($name)
    {
        return Configuration::select('value')->where('name', $name)->first()->value;
    }

    public function path($name, $value)
    {
        if ($name == 'banner_default') {
            $path = Storage::url(config('custom.images.path.banner').$value);
        } else {
            $path = Storage::url(config('custom.images.path.logo').$value);
        }

        return $path;
    }

    static function getImage($name, $type)
    {
        $config = Configuration::select('value')->where('name', $name)->first();
        if ($type == 'logo') {
            if (!empty($config->value)) {
                $img = Storage::url(config('custom.images.path.logo').$config->value);
            } else {
                $img = asset(config('custom.images.file.'.$name));
            }
        }

        if ($type == 'og') {
            if (!empty($config->value)) {
                $img = Storage::url(config('custom.images.path.logo').$config->value);
            } else {
                $img = asset(config('custom.images.file.'.$name));
            }
        }

        if ($type == 'banner') {
            if (!empty($config->value)) {
                $img = Storage::url(config('custom.images.path.banner').$config->value);
            } else {
                $img = asset(config('custom.images.file.banner_default'));
            }
        }

        return $img;
    }
}
