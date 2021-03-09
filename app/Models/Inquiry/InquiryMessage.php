<?php

namespace App\Models\Inquiry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryMessage extends Model
{
    use HasFactory;

    protected $table = 'inquiries_message';
    protected $guarded = [];

    protected $casts = [
        'custom_field' => 'array',
        'submit_time' => 'datetime'
    ];

    public $timestamps = false;

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id');
    }

    public function scopeExport($query)
    {
        return $query->where('exported', 1);
    }
}
