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
use Spatie\Permission\Traits\HasRoles;

class Teacher extends Authenticatable
{
    use Notifiable, SoftDeletes,CascadeSoftDeletes,LogsActivityTrait,HasRoles;

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
        $actions[] = ['key' => 'edit', 'name' => t('Edit'), 'route' => route(getGuard() . '.teacher.edit', $this->id), 'permission' => 'edit teachers'];
        $actions[] = ['key' => 'login', 'name' => t('Login'), 'route' => route(getGuard() . '.teacher.login', $this->id), 'permission' => 'teacher login'];
        $actions[] = ['key' => 'blank', 'name' => t('Report'), 'route' => route(getGuard() . '.teacher.tracking_report', $this->id)];
        if (in_array(getGuard(),['manager','school'])){
            $actions[] = ['key' => 'blank', 'name' => t('Edit Permissions'), 'route' => route(getGuard().'.user_role_and_permission.edit',['user_guard'=>'teacher','id'=>$this->id]),'permission'=>'edit teachers permissions'];
            $actions[] = ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete teachers'];
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
        return $this->morphMany(LoginSession::class, 'model')->where('status', 'success');
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

    // Filter by schools
    public function scopeFilterBySchools(Builder $query, $schools)
    {
        return $query->whereIn('school_id', $schools->pluck('id'));
    }

    // Filter by supervisor
    public function scopeFilterBySupervisor(Builder $query, $guard, $guard_user)
    {
        if ($guard === 'supervisor') {
            $query->whereHas('supervisor_teachers', function (Builder $query) use ($guard_user) {
                $query->where('supervisor_id', $guard_user->id);
            });
        }

        return $query;
    }

    // Filter by grade
    public function scopeFilterByGrade(Builder $query, $grade)
    {
        return $query->whereHas('teacher_users', function (Builder $query) use ($grade) {
            $query->whereHas('user', function (Builder $query) use ($grade) {
                $query->where(function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade)
                        ->orWhere('alternate_grade_id', $grade);
                });
            });
        });
    }

    // Filter by grades
    public function scopeFilterByGrades(Builder $query, $grades)
    {
        return $query->whereHas('teacher_users', function (Builder $query) use ($grades) {
            $query->whereHas('user', function (Builder $query) use ($grades) {
                $query->where(function (Builder $query) use ($grades) {
                    $query->whereIn('grade_id', $grades)
                        ->orWhereIn('alternate_grade_id', $grades);
                });
            });
        });
    }


}
