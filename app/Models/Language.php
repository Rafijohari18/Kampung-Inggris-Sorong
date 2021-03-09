<?php

namespace App\Models;

use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Language extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'languages';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        Language::observe(LogObserver::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function setUpperField($value)
    {
        return strtoupper($value);
    }

    public function urlSwitcher($segment, $lang)
    {
        return Str::replaceFirst($segment, $lang, str_replace(url('/'), '', URL::full()));
    }
}
