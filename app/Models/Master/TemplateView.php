<?php

namespace App\Models\Master;

use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateView extends Model
{
    use HasFactory;

    protected $table = 'template_view';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        TemplateView::observe(LogObserver::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
