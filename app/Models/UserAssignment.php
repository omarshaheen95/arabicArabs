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

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id,'permission'=>'delete lesson assignments'],
            ];
        } elseif (\request()->is('school/*')) {
            $actions = [];
        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id],
            ];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [];
        }
        return view('general.action_menu')->with('actions', $actions);

    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->when($value = $request->get('id',false), function (Builder $query) use ($value){
            $query->where('id', $value);
        })->when($value = $request->get('user_id',false), function (Builder $query) use ($value){
            $query->where('user_id', $value);
        })->when($value = $request->get('user_name',false), function (Builder $query) use ($value){
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        })->when($value = $request->get('user_email',false), function (Builder $query) use ($value){
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('email', 'like', '%' . $value . '%');
            });
        })->when($value = $request->get('lesson_id', false), function (Builder $query) use ($value) {
            $query->where('lesson_id', $value);
        })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '>=', $value);
        })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '<=', $value);
        })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
            $query->where('completed', $value != 2);
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('school_id', $value);
            });
        })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('grade_id', $value);
            });
        })->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->whereHas('teacherUser', function (Builder $query) use ($value) {
                    $query->where('teacher_id', $value);
                });
            });
        })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('section', $value);
            });
        })->when($value = $request->get('user_status', false), function (Builder $query) use ($value) {
            if ($value == 'active') {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('active_to', '>=', now());
                });
            } elseif ($value == 'expire') {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where(function ($q) {
                        $q->where('active_to', '<', now())->orWhereNull('active_to');
                    });
                });
            }
        })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
            $query->whereHas('lesson', function (Builder $query) use ($value) {
                $query->where('grade_id', $value);

            });
        })
            ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value){
                $query->whereRelation('user.teacher.supervisor_teachers','supervisor_id',$value);
            })
            ->when($value = $request->get('row_id', false), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            });
    }
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

}
