<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTracker extends Model
{
    use SoftDeletes;
    //Type 'learn', 'practise', 'test', 'play'
    protected $fillable = [
        'user_id', 'lesson_id', 'type', 'color', 'start_at', 'end_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getTotalTimeAttribute()
    {
        if (!is_null($this->start_at) && !is_null($this->end_at))
        {
            return Carbon::parse($this->start_at)->diffInMinutes($this->end_at);
        }else{
            return '';
        }
    }

    public function getTypeTextAttribute()
    {
        switch ($this->type)
        {
            case 'learn':
                $content = "بدا تعلم";
                return $content;
            case 'practise':
                $content = "بدا تدريب";
                return $content;
            case 'test':
                $content = "بدا اختبار";
                if (!is_null($this->start_at) && !is_null($this->end_at))
                {
                    $start = Carbon::parse($this->start_at);
                    $end = Carbon::parse($this->end_at);
                    $diff = $start->diffInMinutes($end, false);
                    $content .= ' <span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill">'.$diff.' د </span>';
                }
                return $content;
            default:
                return $this->type;
        }
    }
}
