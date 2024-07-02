<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class MotivationalCertificate extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'teacher_id',
        'user_id',
        'model_type',
        'model_id',
        'granted_in',
    ];

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query
            ->has('user')
            ->has('teacher')
            ->hasMorph('model', [Lesson::class, Story::class])
            ->when($value = $request->get('row_id', false), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            })->when($value = $request->get('id', false), function (Builder $query) use ($value) {
                $query->where('id', $value);
            })
            ->when($value = $request->get('teacher_id', false), function (Builder $query) use ($value) {
                $query->where('teacher_id', $value);
            })
            ->when($value = $request->get('user_id', false), function (Builder $query) use ($value) {
                $query->where('user_id', $value);
            })
            ->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('school_id', $value);
                });
            })
            ->when($value = $request->get('name', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%');
                });
            })

            ->when($value = $request->get('user_email', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('email', $value);
                });
            })
            ->when($value = $request->get('email', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('email', $value);
                });
            })
            ->when($value = $request->get('section', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('section', $value);
                });
            })
            ->when($value = $request->get('student_grade', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('grade_id', $value);
                });
            })
            ->when($value = $request->get('user_status', false), function (Builder $query) use ($value) {
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
            })
            ->when($value = $request->get('model_type', false), function (Builder $query) use ($value) {
                $query->where('model_type', $value);
            })
            ->when($value = $request->get('model_id', false), function (Builder $query) use ($value) {
                $query->where('model_id', $value);
            })
            ->when($value= $request->get('start_date',false),function (Builder $query) use ($value){
                $query->whereDate('granted_in', '>=',$value);
            })
            ->when($value= $request->get('end_date',false),function (Builder $query) use ($value){
                $query->whereDate('granted_in', '<=',$value);
            })
            ->when($value= $request->get('start_created',false),function (Builder $query) use ($value){
                $query->whereDate('created_at', '>=',$value);
            })
            ->when($value= $request->get('end_created',false),function (Builder $query) use ($value){
                $query->whereDate('created_at', '<=',$value);
            });
    }

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('manager.motivational_certificate.show', $this->id), 'permission' => 'show motivational certificate'],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete motivational certificate'],
            ];
        } elseif (\request()->is('school/*')) {
            $actions = [

            ];

        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('teacher.motivational_certificate.show', $this->id)],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id],
            ];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [

            ];
        }
        return view('general.action_menu')->with('actions', $actions);

    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }


}
