<?php

namespace App\Models;

use App\Notifications\SupervisorResetPassword;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class Supervisor extends Authenticatable
{
    use Notifiable, SoftDeletes,LogsActivityTrait;

    protected $fillable = [
        'name', 'email','image', 'password', 'school_id', 'active', 'active_to', 'approved','lang','last_login','last_login_info'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('manager.supervisor.edit', $this->id), 'permission' => 'edit supervisors'],
                ['key' => 'login', 'name' => t('Login'), 'route' => route('manager.supervisor.login', $this->id), 'permission' => 'supervisors login'],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete supervisors'],
            ];
        } elseif (\request()->is('school/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('school.supervisor.edit', $this->id)],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id],
            ];
        } else {
            return '';
        }
        return view('general.action_menu')->with('actions', $actions);

    }

    public function scopeFilter(Builder $query, $request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->where('school_id', $value);
        })->when($value = $request->get('email', false), function (Builder $query) use ($value) {
            $query->where('email', 'LIKE', '%' . $value . '%');
        })->when($value = $request->get('name', false), function (Builder $query) use ($value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        })->when($value = $request->get('active', false), function (Builder $query) use ($value) {
            $query->where('active', $value != 2);
        })->when($value = $request->get('approved', false), function (Builder $query) use ($value) {
            $query->where('approved', $value != 2);
        })->when($value = $request->get('id', false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SupervisorResetPassword($token));
    }
    public function login_sessions()
    {
        return $this->morphMany(LoginSession::class, 'model');
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


}
