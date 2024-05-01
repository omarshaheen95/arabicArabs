<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class StoryAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'story_id', 'test_assignment', 'done_test_assignment', 'completed', 'deadline', 'completed_at', 'deadline', 'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
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
                $query->whereHas('user', function (Builder $query) use ($grade) {
                    $query->where('grade_id', $grade);
                });
            })->when($grade = $request->get('story_grade', false), function (Builder $query) use ($grade) {
                $query->whereHas('story', function (Builder $query) use ($grade) {
                    $query->where('grade', $grade);
                });
            })->when($story_id = $request->get('story_id', false), function (Builder $query) use ($story_id) {
                $query->where('story_id', $story_id);
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
