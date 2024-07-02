<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class StudentStoryTest extends Model
{
    use SoftDeletes, CascadeSoftDeletes, LogsActivityTrait;

    protected $fillable = [
        'user_id', 'story_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status'
    ];
    protected static $recordEvents = ['updated'];
    protected $cascadeDeletes = ['storyMatchResults', 'storyOptionResults', 'storySortResults', 'storyTrueFalseResults'];

    //boot with query where has story
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('story', function (Builder $builder) {
            $builder->has('story');
        });
    }

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {

            $actions[] = ['key' => 'show', 'name' => t('Show'), 'route' => route('manager.stories_tests.show', $this->id), 'permission' => 'show story tests'];
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('manager.stories_tests.certificate', $this->id), 'permission' => 'story tests certificate'];
            }
            $actions[] = ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete story tests'];

        } elseif (\request()->is('school/*')) {
            $actions = [
                ['key' => 'show', 'name' => t('Show'), 'route' => route('school.stories_tests.show', $this->id)],
            ];
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('school.stories_tests.certificate', $this->id)];
            }
        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'show', 'name' => t('Show'), 'route' => route('teacher.stories_tests.show', $this->id)],
            ];
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('teacher.stories_tests.certificate', $this->id)];
            }
        } elseif (\request()->is('supervisor/*')) {
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('supervisor.stories_tests.certificate', $this->id)];
            } else {
                return '';
            }
        }
        return view('general.action_menu')->with('actions', $actions);

    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {

        return $query->when($value = $request->get('id', false), function (Builder $query) use ($value) {
            $query->where('id', $value);
        })->when($value = $request->get('user_id', false), function (Builder $query) use ($value) {
            $query->where('user_id', $value);
        })->when($value = $request->get('user_name', false), function (Builder $query) use ($value) {
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
        })->when($value = $request->get('school_id', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('school_id', $value);
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
        })->when($value = $request->get('student_grade', false), function (Builder $query) use ($value) {
            $query->whereHas('user', function (Builder $query) use ($value) {
                $query->where('grade_id', $value);
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
        })->when($value = $request->get('start_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '>=', $value);
        })->when($value = $request->get('end_date', false), function (Builder $query) use ($value) {
            $query->whereDate('created_at', '<=', $value);
        })->when($value = $request->get('grade', false), function (Builder $query) use ($value) {
            $query->whereHas('story', function (Builder $query) use ($value) {
                $query->where('grade', $value);
            });
        })->when($value = $request->get('story_id', false), function (Builder $query) use ($value) {
            $query->where('story_id', $value);
        })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
            $query->where('status', $value);
        })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
            $query->whereIn('id', $value);
        })->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value) {
            $query->whereRelation('user.teacher.supervisor_teachers', 'supervisor_id', $value);
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

    public function storyMatchResults(): HasMany
    {
        return $this->hasMany(StoryMatchResult::class, 'student_story_test_id');
    }

    public function storyOptionResults(): HasMany
    {
        return $this->hasMany(StoryOptionResult::class, 'student_story_test_id');
    }

    public function storySortResults(): HasMany
    {
        return $this->hasMany(StorySortResult::class, 'student_story_test_id');
    }

    public function storyTrueFalseResults(): HasMany
    {
        return $this->hasMany(StoryTrueFalseResult::class, 'student_story_test_id');
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

    public function getTotalPerAttribute()
    {
        return $this->total > 0 ? (($this->total * 2) / 100 * 100) . '%' : '0%';
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


}
