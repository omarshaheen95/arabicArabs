<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAssignment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'lesson_id', 'tasks_assignment', 'test_assignment', 'done_tasks_assignment', 'done_test_assignment', 'completed', 'deadline', 'completed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getCheckAttribute()
    {
        $button = '';
        $button .= " <input type='checkbox' class='user_assignment_id' id='user_assignment_id[$this->id]' value='$this->id'>";
        return $button;
    }

    public function getTeacherActionButtonsAttribute()
    {
        $button = ' <button type="button" data-id="'.$this->id.'" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
        return $button;
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
