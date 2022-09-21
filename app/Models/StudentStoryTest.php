<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentStoryTest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'story_id', 'corrected', 'total', 'notes', 'max_time', 'approved', 'start_at', 'end_at', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function getActionButtonsAttribute()
    {
        $button = '<a target="_blank" href="'.route('manager.story.student_test', $this->id).'" class="btn btn-success">تصحيح </a>';
        $button .= ' <button type="button" data-id="'.$this->id.'" data-toggle="modal" data-target="#deleteModel" class="deleteRecord btn btn-icon btn-warning"><i class="la la-trash"></i></button> ';
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

    public function getTotalPerAttribute()
    {
        return $this->total > 0 ? (($this->total * 2) / 100 * 100)  . '%' :'0%';
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
