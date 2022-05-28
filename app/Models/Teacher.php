<?php

namespace App\Models;

use App\Notifications\TeacherResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable, SoftDeletes;
    protected $fillable = [
        'name', 'email', 'password', 'school_id', 'mobile', 'pending_tasks', 'corrected_tasks', 'returned_tasks',
        'passed_tests', 'failed_tests', 'approved', 'active', 'last_login'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new TeacherResetPassword($token));
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
