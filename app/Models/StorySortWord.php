<?php

namespace App\Models;

use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorySortWord extends Model
{
    use SoftDeletes,LogsActivityTrait;
    protected $fillable = [
        'story_question_id', 'content', 'ordered',
    ];
    protected static $recordEvents = ['updated'];
    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }
}
