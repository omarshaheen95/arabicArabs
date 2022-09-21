<?php

namespace App\Models;

use App\Traits\Pathable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryUserRecord extends Model
{
    use SoftDeletes, Pathable;
    //status 'pending', 'corrected', 'returned'
    protected $fillable = [
        'user_id', 'story_id', 'record', 'mark', 'approved', 'status'
    ];

    public $pathAttribute = [
        'record'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == 'pending')
        {
            return 'قيد المراجعة';
        }else if($this->status == 'corrected'){
            return 'مكتمل التصحيح';
        }else{
            return 'مرجع';
        }
    }

    public function getTeacherActionButtonsAttribute()
    {
        $button = '';
        $button .= '<a href="' . route('teacher.students_record.show', $this->id) . '" class="btn btn-icon btn-danger "><i class="la la-eye"></i></a> ';
//        $button .= '<button type="button" data-id="' . $this->id . '" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-danger"><i class="la la-trash"></i></button>';
        return $button;
    }
}
