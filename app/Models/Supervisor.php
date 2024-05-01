<?php

namespace App\Models;

use App\Notifications\SupervisorResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class Supervisor extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'school_id', 'active', 'approved', 'last_login'
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

    public function supervisor_teachers()
    {
        return $this->hasMany(SupervisorTeacher::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'supervisor_teachers', 'supervisor_id', 'teacher_id')->whereNull('supervisor_teachers.deleted_at');
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

    public function getSchoolActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('school.supervisor.edit', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-pencil"></i></a> ';
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }
}
