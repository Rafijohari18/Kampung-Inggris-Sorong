<?php

namespace App\Models\Master;

use App\Models\Users\User;
use App\Observers\LogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';
    protected $guarded = [];

    protected $casts = [
        'file_path' => 'array',
        'caption' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        Media::observe(LogObserver::class);
    }

    public function mediable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getExtension($file)
    {
        return pathinfo(Storage::url(config('custom.images.path.filemanager').$file))['extension'];
    }

    public function icon($item)
    {
        $type = $item->getExtension($item->file_path['filename']);

        if ($type == 'jpg' || $type == 'jpeg' || $type == 'png' ||
            $type == 'svg' || $type == 'jpg') {
            $ext = 'image';
        } elseif ($type == 'mp4' || $type == 'webm') {
            $ext = 'video';
        } elseif ($type == 'mp3') {
            $ext = 'audio';
        } elseif ($type == 'pdf') {
            $ext = 'pdf';
        } elseif ($type == 'doc' || $type == 'docx') {
            $ext = 'word';
        } elseif ($type == 'ppt' || $type == 'pptx') {
            $ext = 'powerpoint';
        } elseif ($type == 'xls' || $type == 'xlsx') {
            $ext = 'excel';
        } else {
            $ext = 'alt';
        }

        return $ext;
    }
}
