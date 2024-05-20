<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class UserTest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'lesson_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status', 'feedback_message', 'feedback_record'
    ];

    //boot with query where has lesson
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('lesson', function (Builder $builder) {
            $builder->has('lesson');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getActionButtonsAttribute()
    {
        $button = '<a target="_blank" href="' . route('manager.term.student_test', $this->id) . '" class="btn btn-success">تصحيح </a>';
        $button .= ' <button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
        return $button;
    }

    public function getTeacherActionButtonsAttribute()
    {
        $button = "";
        if (in_array($this->lesson->lesson_type, ['writing', 'speaking'])) {
            if ($this->corrected) {
                $button .= ' <a target="_blank" href="' . route('teacher.students_tests.show', $this->id) . '" class="btn btn-info">تم التصحيح </a>';
            } else {
                $button .= ' <a target="_blank" href="' . route('teacher.students_tests.show', $this->id) . '" class="btn btn-success">تصحيح </a>';
            }
        }
        $button .= ' <a target="_blank" href="' . route('teacher.students_tests.preview', $this->id) . '" class="btn btn-success">معاينة </a>';

        return $button;
    }

    public function getReadingBenchmarkAttribute()
    {
        if ($this->total >= 61) {
            return 'Above the expectations';
        } elseif ($this->total >= 41 && $this->total <= 68) {
            return 'In line with the expectations';
        } else {
            return 'Below the expectations';
        }
    }

    public function getExpectationsAttribute()
    {
        if ($this->total >= 61) {
            return 'Above';
        } elseif ($this->total >= 41 && $this->total <= 68) {
            return 'In line';
        } else {
            return 'Below';
        }
    }

    public function speakingResults()
    {
        return $this->hasMany(SpeakingResult::class);
    }

    public function writingResults()
    {
        return $this->hasMany(WritingResult::class);
    }

    public function scopeSearch(Builder $builder, Request $request)
    {
        return $builder->when($request->get('corrected', false) == 1, function (Builder $query) {
            $query->where('corrected', 1);
        })
            ->when($request->get('corrected', false) == 2, function (Builder $query) {
                $query->where('corrected', 0);
            })
            ->when($username = $request->get('username', false), function (Builder $query) use ($username) {
                $query->whereHas('user', function (Builder $query) use ($username) {
                    $query->where('name', 'like', '%' . $username . '%');
                });
            })->when($grade = $request->get('grade', false), function (Builder $query) use ($grade) {
                $query->whereHas('lesson', function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade);
                });
            })->when($lesson_id = $request->get('lesson_id', false), function (Builder $query) use ($lesson_id) {
                $query->where('lesson_id', $lesson_id);
            })->when($start_at = $request->get('start_at', false), function (Builder $query) use ($start_at) {
                $query->where('created_at', '<=', $start_at);
            })->when($end_at = $request->get('end_at', false), function (Builder $query) use ($end_at) {
                $query->where('created_at', '>=', $end_at);
            })->when($status = $request->get('status', false), function (Builder $query) use ($status) {
                $query->where('status', $status);
            })->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->whereHas('teacher', function (Builder $query) use ($value) {
                        $query->whereHas('supervisor_teachers', function (Builder $query) use ($value) {
                            $query->where('supervisor_id', $value);
                        });
                    });
                });
            })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->whereHas('teacherUser', function (Builder $query) use ($value) {
                        $query->where('teacher_id', $value);
                    });
                });
            });
    }

}
