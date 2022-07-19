<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Lesson extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    //lesson type : 'reading', 'writing', 'listening', 'speaking', 'grammar'
    //section type : 'informative', 'literary'
    protected $fillable = [
        'name', 'content', 'grade_id', 'lesson_type', 'section_type', 'ordered', ' success_mark', 'active', 'color'
    ];

    protected $appends = [
        'type_name', 'section_name',
    ];

    public function getTypeNameAttribute()
    {
        switch ($this->lesson_type) {
            case 'reading':
                return 'قراءة';
            case 'writing':
                return 'كتابة';
            case 'listening':
                return 'استماع';
            case 'speaking':
                return 'تحدث';
            case 'grammar':
                return 'قواعد';
            default:
                return 'غير مسجل';
        }
    }

    public function getSectionNameAttribute()
    {
        switch ($this->section_type) {
            case 'informative':
                return 'معلوماتي';
            case 'literary':
                return 'أدبي';
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
        $btn = '<a href="'.route('manager.lesson.learn', $this->id).'" class="btn btn-danger ">التعلم</a> ';

        if ($this->lesson_type == 'reading' || $this->lesson_type == 'listening')
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
            return 'معلوماتي';
        }elseif ($this->section_type == 'literary')
        {
            return 'أدبي';
        }else{
            return null;
        }
    }


}
