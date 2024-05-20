<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class UserAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'lesson_id', 'tasks_assignment', 'test_assignment', 'done_tasks_assignment', 'done_test_assignment', 'completed', 'deadline', 'completed_at'
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

    public function getCheckAttribute()
    {
        $button = '';
        $button .= " <input type='checkbox' class='user_assignment_id' id='user_assignment_id[$this->id]' value='$this->id'>";
        return $button;
    }

    public function getTeacherActionButtonsAttribute()
    {
        $button = ' <button type="button" data-id="'.$this->id.'" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
        return $button;
    }

    public function getSubmitStatusAttribute()
    {
        $status = "";
        if (!is_null($this->deadline) && !is_null($this->completed_at))
        {
            if ($this->deadline < $this->completed_at)
            {
                $status = t('late');
            }else{
                $status = "-";
            }
        }else{
            $status = '-';
        }
        return $status;
    }

    public function scopeSearch(Builder $builder, Request $request)
    {

        return $builder
            ->when($request->get('status', false) == 1, function (Builder $query) {
                $query->where('completed', 1);
            })
            ->when($request->get('status', false) == 2, function (Builder $query) {
                $query->where('completed', 0);
            })
            ->when($username = $request->get('username', false), function (Builder $query) use ($username) {
                $query->whereHas('user', function (Builder $query) use ($username) {
                    $query->where('name', 'like', '%' . $username . '%');
                });
            })->when($grade = $request->get('grade', false), function (Builder $query) use ($grade) {
                $query->whereHas('lesson', function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade);
                });
            })->when($level_id = $request->get('level_id', false), function (Builder $query) use ($level_id) {
                $query->whereHas('lesson', function (Builder $query) use ($level_id) {
                    $query->where('level_id', $level_id);
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
