<?php

namespace App\Models;

use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLesson extends Model
{
    use SoftDeletes, Pathable;
    //status 'pending', 'corrected', 'returned'
    protected $fillable = [
        'user_id', 'lesson_id', 'status', 'teacher_message', 'student_message', 'submitted_at'
    ];

    public $pathAttribute = [
        'attach_writing_answer', 'reading_answer', 'teacher_audio_message'
    ];

    //boot with query where has lesson
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('lesson', function ($builder) {
            $builder->has('lesson');
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


    public function getStatusNameAttribute()
    {
        if ($this->status == 'pending')
        {
            return "قيد الإنتظار";
        }else if($this->status == 'corrected'){
            return "مصحح";
        }else{
            return "مرجع";
        }
    }

    public function getActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('manager.students_works.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }

    public function getSchoolActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('school.students_works.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';

        return $button;
    }

    public function getTeacherActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('teacher.students_works.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
        return $button;
    }

    public function getSupervisorActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('supervisor.students_works.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
        return $button;
    }
}
