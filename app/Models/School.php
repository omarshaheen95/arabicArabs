<?php

namespace App\Models;

use App\Notifications\SchoolResetPassword;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;

class School extends Authenticatable
{
    use Notifiable, SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password', 'website', 'mobile', 'logo', 'active', 'last_login'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SchoolResetPassword($token));
    }

    public function getLogoAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/school.png'):asset($value);
    }

    public function students()
    {
        return $this->hasMany(User::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function getActiveStatusAttribute()
    {
        return $this->active ? 'فعالة':'غير فعالة';
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($name = $request->get('name', false), function($query) use($name){
            $query->where('name', '%' . $name . '%')
                ->orWhere('email', 'like', '%'.$name.'%')
                ->orWhere('mobile', 'like', '%'.$name.'%');
        });
    }
}
