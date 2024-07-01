<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class StoryAssignment extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'user_id', 'story_id', 'test_assignment', 'done_test_assignment', 'completed', 'deadline', 'completed_at', 'deadline', 'completed_at'
    ];

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id,'permission'=>'delete story assignments'],

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
        })->when($value = $request->get('username',false), function (Builder $query) use ($value){
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
            $query->whereHas('story', function (Builder $query) use ($value) {
                $query->where('grade', $value);
            });
        })->when($value = $request->get('story_id', false), function (Builder $query) use ($value) {
            $query->where('story_id', $value);
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
        })->when($value = $request->get('student_grade', false), function (Builder $query) use ($value) {
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
        })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
            $query->whereHas('story', function (Builder $query) use ($value) {
                $query->where('grade', $value);
            });
        })->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value){
                $query->whereRelation('user.teacher.supervisor_teachers','supervisor_id',$value);
            })
            ->when($value = $request->get('row_id', false), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            });
    }


    //boot with query where has story
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('story', function (Builder $builder) {
            $builder->has('story');
        });
    }

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


}
