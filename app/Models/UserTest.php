<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'lesson_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status', 'feedback_message', 'feedback_record'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getActionButtonsAttribute()
    {
        $button = '<a target="_blank" href="'.route('manager.term.student_test', $this->id).'" class="btn btn-success">تصحيح </a>';
        $button .= ' <button type="button" data-id="'.$this->id.'" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
        return $button;
    }

    public function getTeacherActionButtonsAttribute()
    {
        $button = "";
        if(in_array($this->lesson->lesson_type, ['writing', 'speaking'])) {
            if ($this->corrected)
            {
                $button .= ' <a target="_blank" href="'.route('teacher.students_tests.show', $this->id).'" class="btn btn-info">تم التصحيح </a>';
            }else{
                $button .= ' <a target="_blank" href="'.route('teacher.students_tests.show', $this->id).'" class="btn btn-success">تصحيح </a>';
            }
        }
        $button .= ' <a target="_blank" href="'.route('teacher.students_tests.preview', $this->id).'" class="btn btn-success">معاينة </a>';

        return $button;
    }

    public function getReadingBenchmarkAttribute()
    {
        if ($this->total >= 61){
            return 'Above the expectations';
        }elseif ($this->total >= 41 && $this->total <= 68) {
            return 'In line with the expectations';
        }else{
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

    public function speakingResults()
    {
        return $this->hasMany(SpeakingResult::class);
    }

    public function writingResults()
    {
        return $this->hasMany(WritingResult::class);
    }

}
