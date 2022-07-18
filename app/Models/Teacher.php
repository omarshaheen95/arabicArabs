<?php

namespace App\Models;

use App\Notifications\TeacherResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
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

    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($name = $request->get('name', false), function (Builder $query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('email', 'like', '%' . $name . '%');
        })->when($school = $request->get('school_id', false), function (Builder $query) use ($school) {
            $query->where('school_id', $school);
        });
    }

    public function getActiveStatusAttribute()
    {
        return $this->active ? 'فعال':'غير فعال';
    }

    public function getApprovedStatusAttribute()
    {
        return $this->approved ? 'فعال':'غير فعال';
    }

    public function getCheckAttribute()
    {
        $button = '';
        $button .= " <input type='checkbox' class='teacher_id' id='teacher_id[$this->id]' value='$this->id'>";
        return $button;
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/teacher.png'):asset($value);
    }
}
