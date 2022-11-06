<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'school_id', 'grade_id', 'alternate_grade_id', 'year_learning',
        'section', 'country_code', 'short_country',
        'active', 'type', 'active_from', 'active_to', 'package_id', 'manager_id', 'year_id', 'last_login', 'image'
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

    public function school()
    {
        return $this->belongsTo(School::class);
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
            });
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? asset('assets/media/icons/student.png') : asset($value);
    }

    public function user_tracker()
    {
        return $this->hasMany(UserTracker::class);
    }

    public function user_test()
    {
        return $this->hasMany(UserTest::class);
    }

    public function user_grades()
    {
        return $this->hasMany(UserGrade::class);
    }

    public function user_lessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function getCheckAttribute()
    {
        $button = '';
        $button .= " <input type='checkbox' class='user_id' id='user_id[$this->id]' value='$this->id'>";
        return $button;
    }

    public function getGradeNameAttribute()
    {
        switch ($this->grade_id) {
            case 13:
                return "المرحلة التمهيدية";
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
