<?php

namespace App\Models;

use App\Notifications\ManagerResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Manager extends Authenticatable
{
    use Notifiable, SoftDeletes;


    protected $fillable = [
        'name', 'email', 'password', 'active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ManagerResetPassword($token));
    }
}
