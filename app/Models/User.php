<?php

namespace App\Models;

use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, Notifiable,CascadeSoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'school_id', 'grade_id', 'alternate_grade_id', 'year_learning',
        'section', 'country_code', 'short_country', 'id_number',
        'active', 'type','demo_grades', 'active_from', 'active_to', 'package_id', 'manager_id', 'year_id', 'last_login', 'image', 'import_file_id'
        ,'last_login_info'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'active_from', 'active_to'
    ];

    protected $cascadeDeletes = [
        'teacherUser', 'user_tracker', 'user_test', 'user_grades', 'user_lessons', 'user_story_tests',
    ];



    public function scopeFilter(Builder $query, $request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query->when($value = $request->get('id', false), function (Builder $query) use ($value) {
            is_array($value) ? $query->whereIn('id', $value) : $query->where('id', $value);
        })->when($value = $request->get('import_file_id', false), function (Builder $query) use ($value) {
            $query->where('import_student_file_id', $value);
        })->when($value = $request->get('name', false), function (Builder $query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->when($value = $request->get('id_number', false), function (Builder $query) use ($value) {
            $query->where('id_number', 'like', '%' . $value . '%');
        })->when($value = $request->get('email', false), function (Builder $query) use ($value) {
            $query->where('email', $value);
        })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
            $query->where('grade_id', $value);
        })->when($value = $request->get('package_id', false), function (Builder $query) use ($value) {
            $query->where('package_id', $value);
        })->when($value = $request->get('year_id', false), function (Builder $query) use ($value) {
            $query->where('year_id', $value);
        })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
            is_array($value) ? $query->whereIn('section', $value) : $query->where('section', $value);
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->where('school_id', $value);
        })->when($value = $request->get('year_learning', false), function (Builder $query) use ($value) {
            $query->where('year_learning', $value);
        })->when($value = $request->get('package_id', false), function (Builder $query) use ($value) {
            $query->where('package_id', $value);
        })->when($value = $request->get('active', false), function (Builder $query) use ($value) {
                $query->where('active', $value != 2);
            })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
                $query->whereHas('teacherUser', function (Builder $query) use ($value) {
                    is_array($value)?$query->whereIn('teacher_id', $value):$query->where('teacher_id', $value);
                });
            })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
                if ($value == 'active') {
                    $query->where('active_to', '>=', now());
                } elseif ($value == 'expire') {
                    $query->where(function ($q) {
                        $q->where('active_to', '<', now())->orWhereNull('active_to');
                    });
                }
            })->when($value = $request->get('start_register_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value)->startOfDay());
            })->when($value = $request->get('end_register_date', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value)->endOfDay());
            })->when($value = $request->get('start_login_at', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '>=', Carbon::parse($value)->startOfDay());
            })->when($value = $request->get('end_login_at', false), function (Builder $query) use ($value) {
                $query->whereDate('created_at', '<=', Carbon::parse($value)->endOfDay());
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            })->when($value = $request->get('deleted_at',false),function (Builder $query) use ($value){
                if ($value == 1){
                    $query->whereNull('deleted_at');
                }else{
                    $query->whereNotNull('deleted_at')->withTrashed();
                }
            })->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
                $query->whereHas('teacher', function (Builder $query) use ($value) {
                    $query->whereHas('supervisor_teachers', function (Builder $query) use ($value) {
                        $query->where('supervisor_id', $value);
                    });
                });
            });
    }

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            if ($this->deleted_at && Auth::guard('manager')->user()->hasDirectPermission('restore deleted users')) { //
                return '<button  onclick="restore(' . $this->id . ')" class="btn btn-warning d-flex justify-content-center align-items-center h-35px w-90px btn_restore">' . t('Restore') . '</button>';
            } else {
                $actions = [
                    ['key' => 'edit', 'name' => t('Edit'), 'route' => route('manager.user.edit', $this->id), 'permission' => 'edit users'],
                    ['key' => 'review', 'name' => t('Review'), 'route' => route('manager.user.review', $this->id), 'permission' => 'review users'],
                    ['key' => 'story review', 'name' => t('Story Review'), 'route' => route('manager.user.story-review', $this->id), 'permission' => 'review users'],
                    ['key' => 'login', 'name' => t('Login'), 'route' => route('manager.user.login', $this->id), 'permission' => 'users login'],
                    ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete users'],
                ];
            }
        } elseif (\request()->is('school/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('school.student.edit', $this->id)],
                ['key' => 'review', 'name' => t('Review'), 'route' => route('school.user.review', $this->id)],
                ['key' => 'story_review', 'name' => t('Story Review'), 'route' => route('school.user.story-review', $this->id)],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id],
            ];

        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('teacher.student.edit', $this->id)],
                ['key' => 'edit', 'name' => t('Report'), 'route' => route('teacher.user.report', $this->id)],
                ['key' => 'review', 'name' => t('Review'), 'route' => route('teacher.user.review', $this->id)],
                ['key' => 'story_review', 'name' => t('Story Review'), 'route' => route('teacher.user.story-review', $this->id)],
            ];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [
                ['key' => 'review', 'name' => t('Review'), 'route' => route('supervisor.user.review', $this->id)],
                ['key' => 'story_review', 'name' => t('Story Review'), 'route' => route('supervisor.user.story-review', $this->id)],
            ];
        }
        return view('general.action_menu')->with('actions', $actions);

    }



    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function login_sessions()
    {
        return $this->morphMany(LoginSession::class, 'model');
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

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function alternateGrade()
    {
        return $this->belongsTo(Grade::class, 'alternate_grade_id');
    }

    public function teacherUser()
    {
        return $this->hasOne(TeacherUser::class);
    }

    public function teacher()
    {
        return $this->hasOneThrough(Teacher::class, TeacherUser::class, 'user_id', 'id', 'id', 'teacher_id');
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($name = $request->get('name', false), function (Builder $query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })
            ->when($grade = $request->get('grade', false), function (Builder $query) use ($grade) {
                $query->where('grade_id', $grade);
            })
            ->when($school_id = $request->get('school_id', false), function (Builder $query) use ($school_id) {
                $query->where('school_id', $school_id);
            })
            ->when($package_id = $request->get('package_id', false), function (Builder $query) use ($package_id) {
                $query->where('package_id', $package_id);
            })
            ->when($status = $request->get('status', false) == 'active', function (Builder $query) {
                $query->where('active_to', '>=', now());
            })
            ->when($status = $request->get('status', false) == 'expire', function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('active_to', '<', now())
                        ->orWhereNull('active_to');
                });
            })
            ->when($section = $request->get('section', false), function (Builder $query) use ($section) {
                $query->where('section', $section);
            })
            ->when($created_at = $request->get('created_at', false), function (Builder $query) use ($created_at) {
                $query->whereDate('created_at', '>=', $created_at);
            })
            ->when($teacher_id = $request->get('teacher_id', false), function (Builder $query) use ($teacher_id) {
                $query->whereHas('teacherUser', function (Builder $query) use ($teacher_id) {
                    $query->where('teacher_id', $teacher_id);
                });
            })->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
                $query->whereHas('teacher', function (Builder $query) use ($value) {
                    $query->whereHas('supervisor_teachers', function (Builder $query) use ($value) {
                        $query->where('supervisor_id', $value);
                    });
                });
            });
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/student.png') : asset($value);
    }
    public function getDemoAttribute()
    {
        return (bool)$this->demo_grades;
    }
    public function getDemoGradesAttribute($value)
    {
        return $value?json_decode($value):[];
    }

    public function setDemoGradesAttribute($value)
    {
        $this->attributes['demo_grades']=json_encode($value);
    }

    public function user_tracker()
    {
        return $this->hasMany(UserTracker::class);
    }


    public function user_tracker_story()
    {
        return $this->hasMany(UserTrackerStory::class);
    }

    public function user_test()
    {
        return $this->hasMany(UserTest::class);
    }

    public function user_grades()
    {
        return $this->hasMany(UserGrade::class);
    }
    public function user_story_tests()
    {
        return $this->hasMany(StudentStoryTest::class);
    }
    public function user_lessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function user_assignments()
    {
        return $this->hasMany(UserAssignment::class);
    }

    public function user_story_assignments()
    {
        return $this->hasMany(StoryAssignment::class);
    }
    public function getGradeNameAttribute()
    {
        switch ($this->grade_id) {

            case 1:
                return "الأول";
            case 2:
                return "الثاني";
            case 3:
                return "الثالث";
            case 4:
                return "الرابع";
            case 5:
                return "الخامس";
            case 6:
                return "السادس";
            case 7:
                return "السابع";
            case 8:
                return "الثامن";
            case 9:
                return "التاسع";
            case 10:
                return "العاشر";
            case 11:
                return "الحادي عشر";
            case 12:
                return "الثاني عشر";
            case 13:
                return "الصف التمهيدي";
            default:
                return '';
        }
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim(strtolower($value));
    }


}
