<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class UserLesson extends Model
{
    use SoftDeletes, Pathable, LogsActivityTrait;

    //status 'pending', 'corrected', 'returned'
    protected $fillable = [
        'user_id', 'lesson_id', 'status', 'teacher_message', 'student_message', 'submitted_at'
    ];

    public $pathAttribute = [
        'attach_writing_answer', 'reading_answer', 'teacher_audio_message'
    ];
    public const STATUS_MODEL = ['pending' => 'Waiting List', 'corrected' => 'Marking Completed', 'returned' => 'Send back'];

    //boot with query where has lesson
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('lesson', function ($builder) {
            $builder->has('lesson');
        });
    }


    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'show', 'name' => t('Show'), 'route' => route('manager.students_works.show', $this->id), 'permission' => 'marking user works'],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete user works'],
            ];
        } elseif (\request()->is('school/*')) {

        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'show', 'name' => t('Show'), 'route' => route('teacher.students_works.show', $this->id)],
            ];
        } elseif (\request()->is('supervisor/*')) {

        }
        return view('general.action_menu')->with('actions', $actions);

    }


    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query->when($value = $request->get('username', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        })->when($value = $request->get('user_email', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('email', $value);
            });
        })->when($value = $request->get('gender', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('gender', $value);
                });
            })->when($value = $request->get('user_id', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('id', $value);
                });
            })->when($value = $request->get('user_status', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    if ($value == 'active') {
                        $query->where('active_to', '>=', now());
                    } elseif ($value == 'expire') {
                        $query->where(function ($q) {
                            $q->where('active_to', '<', now())->orWhereNull('active_to');
                        });
                    }
                });
            })->when($value = $request->get('section', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    is_array($value) ? $query->whereIn('section', $value) : $query->where('section', $value);
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
            })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
                $query->whereHas('user', function (Builder $query) use ($value) {
                    $query->where('school_id', $value);
                });
            })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
                $query->whereHas('lesson', function (Builder $query) use ($value) {
                    $query->where('grade_id', $value);
                });
            })->when($value = $request->get('lesson_id', false), function (Builder $query) use ($value) {
                $query->where('lesson_id', $value);

            })->when($value = $request->get('id', false), function (Builder $query) use ($value) {
                $query->where('id', $value);

            })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
                $query->where('status', $value);

            })
            ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
                $query->whereRelation('user.teacher.supervisor_teachers', 'supervisor_id', $value);
            })
            ->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);

            });
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function lessonStatus()
    {
        return ['pending', 'corrected', 'returned'];
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == 'pending') {
            return t(ucfirst('Waiting list'));
        } else if ($this->status == 'corrected') {
            return t(ucfirst('Marking Completed'));
        } else {
            return t(ucfirst('Send back'));
        }
    }


    public function getStatusNameClassAttribute()
    {
        if ($this->status == 'pending') {
            return '<span class="badge badge-primary">' . t(ucfirst('Waiting list')) . '</span>';
        } else if ($this->status == 'corrected') {
            return '<span class="badge badge-success">' . t(ucfirst('Marking Completed')) . '</span>';
        } else {
            return '<span class="badge badge-warning">' . t(ucfirst('Send back')) . '</span>';
        }
    }

}
