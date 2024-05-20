<?php

namespace App\Models;

use App\Notifications\TeacherResetPassword;
use Carbon\Carbon;
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
        'passed_tests', 'failed_tests', 'approved', 'active', 'last_login', 'passed_tests_lessons', 'failed_tests_lessons'
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

    public function students()
    {
        return $this->hasMany(TeacherUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'teacher_users', 'teacher_id', 'user_id')->whereNull('teacher_users.deleted_at');
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($name = $request->get('name', false), function (Builder $query) use ($name) {
            $query->where(function (Builder $query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%');
            });
        })->when($school = $request->get('school_id', false), function (Builder $query) use ($school) {
            $query->where('school_id', $school);
        })->when($request->get('approved', 1) == 1, function (Builder $query) use ($school) {
            $query->where('approved', 1);
        })->when($request->get('approved', 1) == 2, function (Builder $query) use ($school) {
            $query->where('approved', 0);
        })->when($request->get('student_status') == 1, function (Builder $query) {
            $query->has('students');
        })->when($request->get('student_status') == 2, function (Builder $query) {
            $query->doesntHave('students');
        })->when($request->get('student_status') == 3, function (Builder $query) {
            $query->whereHas('students', function (Builder $query) {
                $query->where('active_to', '>=', now());
            });
        })->when($request->get('student_status') == 4, function (Builder $query) {
            $query->whereHas('students', function (Builder $query) {
                $query->where('active_to', '<', now());
            });
        })
            ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
                $query->whereHas('supervisor_teachers', function (Builder $query) use ($value) {
                    $query->where('supervisor_id', $value);
                });
            });
    }

    public function getActiveStatusAttribute()
    {
        return $this->active ? 'فعال' : 'غير فعال';
    }

    public function getApprovedStatusAttribute()
    {
        return $this->approved ? 'فعال' : 'غير فعال';
    }

    public function getCheckAttribute()
    {
        $button = '';
        $button .= " <input type='checkbox' class='teacher_id' id='teacher_id[$this->id]' value='$this->id'>";
        return $button;
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/teacher.png') : asset($value);
    }

    public function teacher_users()
    {
        return $this->hasMany(TeacherUser::class);
    }

    public function supervisor_teachers()
    {
        return $this->hasMany(SupervisorTeacher::class);
    }


}
