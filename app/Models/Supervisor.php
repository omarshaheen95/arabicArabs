<?php

namespace App\Models;

use App\Notifications\SupervisorResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Supervisor extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'school_id', 'active', 'last_login'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SupervisorResetPassword($token));
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
