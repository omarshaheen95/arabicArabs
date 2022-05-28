<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiddenLesson extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'school_id', 'lesson_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
