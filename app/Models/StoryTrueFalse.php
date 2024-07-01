<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoryTrueFalse extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'story_question_id', 'result'
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }
}
