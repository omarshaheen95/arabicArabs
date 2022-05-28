<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'school_id', 'grade', 'alternate_grade', 'year_learning', 'section', 'country_code', 'short_country',
        'active', 'type', 'active_from', 'active_to', 'package_id', 'manager_id', 'year_id', 'last_login'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
