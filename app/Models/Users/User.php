<?php

namespace App\Models\Users;

use App\Observers\LogObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active_at' => 'datetime',
        'profile_photo_path' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public static function boot()
    {
        parent::boot();

        User::observe(LogObserver::class);
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function info()
    {
        return $this->hasOne(UserInformation::class, 'user_id');
    }

    public function session()
    {
        return $this->hasOne(Session::class, 'user_id');
    }

    public function scopeVerified($query)
    {
        return $query->where('email_verified', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getPhoto($photo)
    {
        if (!empty($photo)) {
            $path = Storage::url(config('custom.images.path.photo').$photo);
        } else {
            $path = asset(config('custom.images.file.photo'));
        }

        return $path;
    }
}
