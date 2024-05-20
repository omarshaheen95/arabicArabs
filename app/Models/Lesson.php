<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Lesson extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, CascadeSoftDeletes;

    //lesson type : 'reading', 'writing', 'listening', 'speaking', 'grammar', 'dictation', 'rhetoric'
    //section type : 'informative', 'literary'
    protected $fillable = [
        'name', 'content', 'grade_id', 'lesson_type', 'section_type', 'ordered', ' success_mark', 'active', 'color', 'level'
    ];

    protected $appends = [
        'type_name', 'section_name',
    ];

    protected $cascadeDeletes = ['questions', 't_questions', 'userAssignments', 'userTests', 'userLessons', 'userTracks'];

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

    public function scopeSearch(Builder $query, Request $request)
    {
        return
            $query->when($name = $request->get('name', false), function ($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            })->when($grade = $request->get('grade_id', false), function ($query) use ($grade) {
                $query->where('grade_id', $grade);
            })->when($lesson_type = $request->get('lesson_type', false), function ($query) use ($lesson_type) {
                $query->where('lesson_type', $lesson_type);
            })->when($id_num = $request->get('id', false), function ($query) use ($id_num) {
                $query->where('id', $id_num);
            })->when($section_type = $request->get('section_type', false), function ($query) use ($section_type) {
                if ($section_type != 'general')
                {
                    $query->where('section_type', $section_type);
                }else{
                    $query->whereNull('section_type');
                }

            });
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

    public function getContentBtnAttribute()
    {
        $training_practices = ['reading','listening','grammar','dictation','rhetoric'];
        $btn = '<a href="'.route('manager.lesson.learn', $this->id).'" class="btn btn-danger ">التعلم</a> ';

        if (in_array($this->lesson_type, $training_practices))
        {
            $btn .= '<a href="'.route('manager.lesson.training', $this->id).'" class="btn btn-danger ">التدريب</a> ';
        }
        $btn .= '<a href="'.route('manager.lesson.assessment', $this->id).'" class="btn btn-danger ">الاختبار</a>';

        return $btn;
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


}
