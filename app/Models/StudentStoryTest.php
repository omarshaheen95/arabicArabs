<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class StudentStoryTest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'story_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function getActionButtonsAttribute()
    {
        $button = '<a target="_blank" href="'.route('manager.story.student_test', $this->id).'" class="btn btn-success">تصحيح </a>';
        $button .= ' <button type="button" data-id="'.$this->id.'" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
        return $button;
    }

    public function getReadingBenchmarkAttribute()
    {
        if ($this->total >= 61){
            return 'Above the expectations';
        }elseif ($this->total >= 41 && $this->total <= 68) {
            return 'In line with the expectations';
        }else{
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

    public function getTotalPerAttribute()
    {
        return $this->total > 0 ? (($this->total * 2) / 100 * 100)  . '%' :'0%';
    }

//    public function getStatusAttribute()
//    {
//        if ($this->total >= 40)
//        {
//            return t('Pass');
//        }else{
//            return t('Fail');
//        }
//    }

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
