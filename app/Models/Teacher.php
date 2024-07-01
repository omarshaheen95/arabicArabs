<?php

namespace App\Models;

use App\Notifications\TeacherResetPassword;
use App\Traits\LogsActivityTrait;
use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable, SoftDeletes,CascadeSoftDeletes,LogsActivityTrait;

    protected $fillable = [
        'name', 'email', 'password', 'image', 'school_id', 'mobile', 'pending_tasks', 'corrected_tasks', 'returned_tasks',
        'passed_tests', 'failed_tests', 'approved', 'active','active_to','lang','last_login','last_login_info', 'passed_tests_lessons', 'failed_tests_lessons',
        'import_file_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $cascadeDeletes = [
        'teacher_users','supervisor_teachers'
    ];

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('manager.teacher.edit', $this->id), 'permission' => 'edit teachers'],
                ['key' => 'login', 'name' => t('Login'), 'route' => route('manager.teacher.login', $this->id), 'permission' => 'teacher login'],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete teachers'],
            ];
        } elseif (\request()->is('school/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('school.teacher.edit', $this->id)],
                ['key' => 'login', 'name' => t('Login'), 'route' => route('school.teacher.login', $this->id)],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id],
            ];
        } elseif (\request()->is('teacher/*')) {
            $actions = [];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [];
        }
        return view('general.action_menu')->with('actions', $actions);

    }


    public function scopeFilter(Builder $query,$request = null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id', false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('import_file_id', false), function (Builder $query) use ($value) {
            $query->where('import_file_id', $value);
        })->when($value = $request->get('name', false), function (Builder $query) use ($value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        })->when($value = $request->get('email', false), function (Builder $query) use ($value) {
            $query->where('email', $value);
        })->when($value = $request->get('mobile', false), function (Builder $query) use ($value) {
            $query->where('mobile', $value);
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->where('school_id', $value);
        })->when($value = $request->get('active', false), function (Builder $query) use ($value) {
            $query->where('active', $value != 2);
        })->when($value = $request->get('approved', false), function (Builder $query) use ($value) {
            $query->where('approved', $value != 2);
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);;
        })->when($value = $request->get('start_active_to', false), function (Builder $query) use ($value) {
            $query->where('active_to', '>=', Carbon::parse($value)->startOfDay());
        })->when($value = $request->get('end_active_to', false), function (Builder $query) use ($value) {
            $query->where('active_to', '<=', Carbon::parse($value)->endOfDay());
        })->when($value = $request->get('start_login_at', false), function (Builder $query) use ($value) {
            $query->where('last_login', '>=', Carbon::parse($value)->startOfDay());
        })->when($value = $request->get('end_login_at', false), function (Builder $query) use ($value) {
            $query->where('last_login', '<=', Carbon::parse($value)->endOfDay());
        })->when($request->get('student_status') == 1, function (Builder $query) {
            $query->has('users');
        })->when($request->get('student_status') == 2, function (Builder $query) {
            $query->doesntHave('users');
        })->when($request->get('student_status') == 3, function (Builder $query) {
            $query->whereHas('users', function (Builder $query) {
                $query->where('active_to', '>=', now());
            });
        })->when($request->get('student_status') == 4, function (Builder $query) {
            $query->whereHas('users', function (Builder $query) {
                $query->where('active_to', '<', now());
            });
        })
            ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
                $query->whereHas('supervisor_teachers', function (Builder $query) use ($value) {
                    $query->where('supervisor_id', $value);
                });
            });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new TeacherResetPassword($token));
    }
    public function login_sessions()
    {
        return $this->morphMany(LoginSession::class, 'model');
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

    public function getImageAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/teacher.png') : asset($value);
    }

    public function teacher_users()
    {
        return $this->hasMany(TeacherUser::class);
    }
    public function getActiveToAttribute($value)
    {
        return is_null($value) ? null : Carbon::parse($value)->format('Y-m-d');
    }
    public function supervisor_teachers()
    {
        return $this->hasMany(SupervisorTeacher::class);
    }


}
