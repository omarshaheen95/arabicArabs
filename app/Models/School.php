<?php

namespace App\Models;

use App\Notifications\SchoolResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;

class School extends Authenticatable
{
    use Notifiable, SoftDeletes, InteractsWithMedia;
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
}
