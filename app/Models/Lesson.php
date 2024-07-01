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
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Lesson extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, CascadeSoftDeletes,LogsActivityTrait;

    //lesson type : 'reading', 'writing', 'listening', 'speaking', 'grammar', 'dictation', 'rhetoric'
    //section type : 'informative', 'literary'
    protected $fillable = [
        'name', 'content', 'grade_id', 'lesson_type', 'section_type', 'ordered', ' success_mark', 'active', 'color', 'level'
    ];

    protected $appends = [
        'type_name', 'section_name',
    ];

    protected $cascadeDeletes = ['questions', 't_questions', 'userAssignments', 'userTests', 'userLessons', 'userTracks'];


    public function scopeFilter(Builder $query,$request =null): Builder
    {
        if (!$request){
            $request = \request();
        }
        return $query
            ->when($value = $request->get('id', false), function (Builder $query) use ($value) {
                $query->where('id', $value);
            })->when($value = $request->get('row_id', []), function (Builder $query) use ($value) {
                $query->whereIn('id', $value);
            })->when($value = $request->get('grade_id', false), function (Builder $query) use ($value) {
                $query->where('grade_id', $value);

            })->when($value = $request->get('name', false), function (Builder $query) use ($value) {
                $query->where('name', $value);

            })->when($value = $request->get('level', false), function (Builder $query) use ($value) {
                $query->where('level', $value);

            })->when($value = $request->get('lesson_type', false), function (Builder $query) use ($value) {
                $query->where('lesson_type', $value);

            })->when($value = $request->get('section_type', false), function (Builder $query) use ($value) {
                $query->where('section_type', $value);

            })->when($value = $request->get('active', false), function (Builder $query) use ($value) {
                $query->where('active', $value != 2);
            })->when($value = $request->get('hidden_status', false) == 1, function (Builder $query) use ($request) {
                $query->whereDoesntHave('hiddenLessons', function ($query) use ($request) {
                    $query->where('school_id', Auth::guard('school')->user()->id);
                });
            })
            ->when($value = $request->get('hidden_status', false) == 2, function (Builder $query) use ($request) {
                $query->whereHas('hiddenLessons', function ($query) use ($request) {
                    $query->where('school_id', Auth::guard('school')->user()->id);
                });
            });
    }

    public function getActionButtonsAttribute()
    {
        $actions = [];
        if (\request()->is('manager/*')) {
            $actions = [
                ['key' => 'edit', 'name' => t('Edit'), 'route' => route('manager.lesson.edit', $this->id), 'permission' => 'edit lessons'],
                ['key' => 'learn', 'name' => t('Learn'), 'route' => route('manager.lesson.learn', $this->id), 'permission' => 'edit lesson learn'],
                ['key' => 'training', 'name' => t('Training'), 'route' => route('manager.lesson.training.index', $this->id), 'permission' => 'show lesson training'],
                ['key' => 'blank', 'name' => t('Training Preview'), 'route' => route('manager.lesson.review', [$this->id, 'training']), 'permission' => 'lesson review'],
                ['key' => 'assessment', 'name' => t('Assessment'), 'route' => route('manager.lesson.assessment.index', $this->id), 'permission' => 'show lesson assessment'],
                ['key' => 'blank', 'name' => t('Assessment Preview'), 'route' => route('manager.lesson.review', [$this->id, 'test']), 'permission' => 'lesson review'],
                ['key' => 'delete', 'name' => t('Delete'), 'route' => $this->id, 'permission' => 'delete lessons'],
            ];
        } elseif (\request()->is('school/*')) {
            $actions = [];
        } elseif (\request()->is('teacher/*')) {
            $actions = [];
        } elseif (\request()->is('supervisor/*')) {
            $actions = [];
        }
        return view('general.action_menu')->with('actions', $actions);

    }

    public function getTypeNameAttribute()
    {
        switch ($this->lesson_type) {
            case 'reading':
                return 'القراءة';
            case 'writing':
                return 'الكتابة';
            case 'listening':
                return 'الاستماع';
            case 'speaking':
                return 'التحدث';
            case 'grammar':
                return 'القواعد النحوية';
            case 'dictation':
                return 'الإملاء';
            case 'rhetoric':
                return 'البلاغة';
            default:
                return 'غير مسجل';
        }
    }

    public function getSectionNameAttribute()
    {
        switch ($this->section_type) {
            case 'informative':
                return 'المعلوماتي';
            case 'literary':
                return 'الأدبي';
            default:
                return 'عام';
        }
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('imageLessons')
            ->singleFile();
        $this
            ->addMediaCollection('audioLessons')
            ->singleFile();
        $this
            ->addMediaCollection('videoLessons');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('minImage')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }


    public function getSectionTypeNameAttribute()
    {
        if ($this->section_type == 'informative')
        {
            return 'المعلوماتي';
        }elseif ($this->section_type == 'literary')
        {
            return 'الأدبي';
        }else{
            return null;
        }
    }

    public function getStudentTestedAttribute()
    {
        $student_test = UserTest::query()->where('lesson_id', $this->id)->where('user_id', Auth::user()->id)
            ->where('approved', 1)->where('total', '>=', 50)->first();
        if ($student_test)
        {
            return true;
        }
        return false;
    }

    public function getStudentTestedTaskAttribute()
    {
        $student_test = UserTest::query()->where('lesson_id', $this->id)->where('user_id', Auth::user()->id)
            ->first();
        if ($student_test)
        {
            return true;
        }
        return false;
    }

    public function getGradeNameAttribute()
    {
        switch($this->grade_id)
        {
            case 1:
                return "الأول = Year 2";
            case 2:
                return "الثاني = Year 3";
            case 3:
                return "الثالث = Year 4";
            case 4:
                return "الرابع = Year 5";
            case 5:
                return "الخامس = Year 6";
            case 6:
                return "السادس = Year 7";
            case 7:
                return "السابع = Year 8";
            case 8:
                return "الثامن = Year 9";
            case 9:
                return "التاسع = Year 10";
            case 10:
                return "العاشر = Year 11";
            case 11:
                return "الحادي عشر = Year 12";
            case 12:
                return "الثاني عشر = Year 13";
            case 13:
                return "الصف التمهيدي = Year 1";
            default:
                return '';
        }
    }

    public static function lessonTypes()
    {
        return['reading', 'writing', 'listening', 'speaking', 'grammar', 'dictation', 'rhetoric'];
    }
    public static function sectionTypes()
    {
        return['informative', 'literary'];
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function t_questions()
    {
        return $this->hasMany(TQuestion::class);
    }

    public function userAssignments()
    {
        return $this->hasMany(UserAssignment::class);
    }

    public function userTests()
    {
        return $this->hasMany(UserTest::class);
    }

    public function userLessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function userTracks()
    {
        return $this->hasMany(UserTracker::class);
    }

    public function hiddenLessons(): HasMany
    {
        return $this->hasMany(HiddenLesson::class, 'lesson_id');
    }
}
