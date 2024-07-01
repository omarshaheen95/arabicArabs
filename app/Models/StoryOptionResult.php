<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryOptionResult extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'story_question_id', 'story_option_id', 'student_story_test_id'
    ];
    protected static $recordEvents = ['updated'];
    public function student_story_test()
    {
        return $this->belongsTo(StudentStoryTest::class);
    }

    public function question()
    {
        return $this->belongsTo(StoryQuestion::class, 'story_question_id');
    }

    public function option()
    {
        return $this->belongsTo(StoryOption::class, 'story_option_id');
    }
}
