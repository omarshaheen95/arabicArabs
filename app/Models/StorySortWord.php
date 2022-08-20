<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StorySortWord extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'story_question_id', 'content', 'ordered',
    ];

    public function question()
    {
        return $this->belongsTo(StoryQuestion::class);
    }
}