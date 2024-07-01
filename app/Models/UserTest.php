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

class UserTest extends Model
{
    use SoftDeletes,CascadeSoftDeletes,LogsActivityTrait;

    protected $fillable = [
        'user_id', 'lesson_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status', 'feedback_message', 'feedback_record'
    ];
    protected $cascadeDeletes = ['matchResults', 'optionResults', 'sortResults', 'trueFalseResults','speakingResults'
     ,'writingResults'];

    //boot with query where has lesson
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('lesson', function (Builder $builder) {
            $builder->has('lesson');
        });
    }


    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {

            $actions[] = ['key' => 'show', 'name' => t('Show'), 'route' => route('manager.lessons_tests.show', $this->id),'permission'=>'show lesson tests'];
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('manager.lessons_tests.certificate', $this->id),'permission'=>'lesson tests certificate'];
            }
            $actions[] = ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete lesson tests'];

        } elseif (\request()->is('school/*')) {
            $actions = [
                ['key' => 'show', 'name' => t('Show'), 'route' => route('school.lessons_tests.show', $this->id)],
            ];
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('school.lessons_tests.certificate', $this->id)];
            }
        } elseif (\request()->is('teacher/*')) {
            $actions = [
                ['key' => 'show', 'name' => t('Show'), 'route' => route('teacher.lessons_tests.show', $this->id)],
            ];
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('teacher.lessons_tests.certificate', $this->id)];
            }
        } elseif (\request()->is('supervisor/*')) {
            if ($this->status == 'Pass') {
                $actions[] = ['key' => 'blank', 'name' => t('Certificate'), 'route' => route('supervisor.lessons_tests.certificate', $this->id)];
            }else{
                return  '';
            }
        } elseif (\request()->is('supervisor/*')) {
            $actions = [];
        }
        return view('general.action_menu')->with('actions', $actions);

    }


    public function scopeFilter(Builder $query, Request $request): Builder
    {

        return $query
            ->when($value = $request->get('id', false), function (Builder $query) use ($value) {
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
            })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
                $query->whereHas('lesson', function (Builder $query) use ($value) {
                    $query->where('grade_id', $value);

                });
            })->when($value = $request->get('lesson_id', false), function (Builder $query) use ($value) {
                $query->where('lesson_id', $value);
            })->when($value = $request->get('status', false), function (Builder $query) use ($value) {
                $query->where('status', $value);
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            }) ->when($value = $request->get('supervisor_id', false), function (Builder $query) use ($value){
                $query->whereRelation('user.teacher.supervisor_teachers','supervisor_id',$value);
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
    public function matchResults(): HasMany
    {
        return $this->hasMany(MatchResult::class, 'user_test_id');
    }

    public function optionResults(): HasMany
    {
        return $this->hasMany(OptionResult::class, 'user_test_id');
    }

    public function sortResults(): HasMany
    {
        return $this->hasMany(SortResult::class, 'user_test_id');
    }

    public function trueFalseResults(): HasMany
    {
        return $this->hasMany(TrueFalseResult::class, 'user_test_id');
    }
    public function speakingResults()
    {
        return $this->hasMany(SpeakingResult::class, 'user_test_id');
    }

    public function writingResults()
    {
        return $this->hasMany(WritingResult::class, 'user_test_id');
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

    public function getStatusNameAttribute()
    {
        return t($this->status);
    }

//    public function getActionButtonsAttribute()
//    {
//        $button = '<a target="_blank" href="' . route('manager.term.student_test', $this->id) . '" class="btn btn-success">تصحيح </a>';
//        $button .= ' <button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
//        return $button;
//    }
//
//    public function getTeacherActionButtonsAttribute()
//    {
//        $button = "";
//        if (in_array($this->lesson->lesson_type, ['writing', 'speaking'])) {
//            if ($this->corrected) {
//                $button .= ' <a target="_blank" href="' . route('teacher.students_tests.show', $this->id) . '" class="btn btn-info">تم التصحيح </a>';
//            } else {
//                $button .= ' <a target="_blank" href="' . route('teacher.students_tests.show', $this->id) . '" class="btn btn-success">تصحيح </a>';
//            }
//        }
//        $button .= ' <a target="_blank" href="' . route('teacher.students_tests.preview', $this->id) . '" class="btn btn-success">معاينة </a>';
//
//        return $button;
//    }
}
