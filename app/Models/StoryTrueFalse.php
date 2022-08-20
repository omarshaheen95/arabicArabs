<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryTrueFalse extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'story_question_id', 'result'
    ];

    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }
}
